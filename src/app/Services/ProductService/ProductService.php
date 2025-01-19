<?php

namespace App\Services\ProductService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\BaseService\BaseService;
use App\Services\ProductService\ProductServiceInterface;
use App\Models\Product;
use App\Services\LocalFileService\LocalFileServiceInterface;
use App\Services\LocalFileService\LocalFileService;

class ProductService extends BaseService implements ProductServiceInterface {
    protected LocalFileServiceInterface $localFileService;
    protected string $product_image_directory;

    public function __construct(Product $model, LocalFileService $localFileService) {
        parent::__construct($model);
        $this->localFileService = $localFileService;
        $this->product_image_directory = config('filesystems.uploaded_files_directory');
    }

    public function create(array $data): Product {
        return DB::transaction(function () use ($data) {
            try {
                // Handle image upload if an image is provided.
                if (isset($data['image']) && $data['image']) {
                    $uploadResult = $this->localFileService->uploadFile(
                        $data['image'],
                        $this->product_image_directory
                    );
                    $data['image'] = $uploadResult['path'];
                }

                // Create the product.
                $product = Product::create($data);
                return $product;
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function update($id, array $data): Product {
        return DB::transaction(function () use ($data, $id) {
            try {
                // Find the product.
                $product = Product::findOrFail($id);

                // Handle image upload if an image is provided.
                if (isset($data['image']) && $data['image']) {
                    // Delete the old image if it exists.
                    if ($product->image) {
                        $this->localFileService->deleteFile($product->image);
                    }

                    $uploadResult = $this->localFileService->uploadFile(
                        $data['image'],
                        $this->product_image_directory
                    );
                    $data['image'] = $uploadResult['path'];
                }

                // Update the product.
                $product->update($data);
                return $product;
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function delete($id): bool {
        return DB::transaction(function () use ($id) {
            try {
                // Find the product.
                $product = Product::findOrFail($id);

                // Delete the product's image if it exists.
                if ($product->image) {
                    $this->localFileService->deleteFile($product->image);
                }

                // Delete the product.
                return $product->delete();
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

}
