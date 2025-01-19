<?php

namespace App\Services\CompanyService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\BaseService\BaseService;
use App\Services\CompanyService\CompanyServiceInterface;
use App\Models\Company;
use App\Services\LocalFileService\LocalFileServiceInterface;
use App\Services\LocalFileService\LocalFileService;

class CompanyService extends BaseService implements CompanyServiceInterface {
    protected LocalFileServiceInterface $localFileService;
    protected string $company_image_directory;

    public function __construct(Company $model, LocalFileService $localFileService) {
        parent::__construct($model);
        $this->localFileService = $localFileService;
        $this->company_image_directory = config('filesystems.uploaded_files_directory');
    }

    public function create(array $data): Company {
        return DB::transaction(function () use ($data) {
            try {
                // Handle image upload if an image is provided.
                if (isset($data['image']) && $data['image']) {
                    $uploadResult = $this->localFileService->uploadFile(
                        $data['image'],
                        $this->company_image_directory
                    );
                    $data['image'] = $uploadResult['path'];
                }

                // Create the company.
                $company = Company::create($data);
                return $company;
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function update($id, array $data): Company {
        return DB::transaction(function () use ($data, $id) {
            try {
                // Find the company.
                $company = Company::findOrFail($id);

                // Handle image upload if an image is provided.
                if (isset($data['image']) && $data['image']) {
                    // Delete the old image if it exists.
                    if ($company->image) {
                        $this->localFileService->deleteFile($company->image);
                    }

                    $uploadResult = $this->localFileService->uploadFile(
                        $data['image'],
                        $this->company_image_directory
                    );
                    $data['image'] = $uploadResult['path'];
                }

                // Update the company.
                $company->update($data);
                return $company;
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function delete($id): bool {
        return DB::transaction(function () use ($id) {
            try {
                // Find the company.
                $company = Company::findOrFail($id);

                // Delete the company's image if it exists.
                if ($company->image) {
                    $this->localFileService->deleteFile($company->image);
                }

                // Delete the company.
                return $company->delete();
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

}
