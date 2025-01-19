<?php

namespace App\Services\UserService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Services\BaseService\BaseService;
use App\Services\UserService\UserServiceInterface;
use App\Models\User;
use App\Services\LocalFileService\LocalFileServiceInterface;
use App\Services\LocalFileService\LocalFileService;
use App\Events\UserCreated;

class UserService extends BaseService implements UserServiceInterface {
    protected LocalFileServiceInterface $localFileService;
    protected string $user_image_directory;

    public function __construct(User $model, LocalFileService $localFileService) {
        parent::__construct($model);
        $this->localFileService = $localFileService;
        $this->user_image_directory = config('filesystems.uploaded_files_directory');
    }

    public function create(array $data): User {
        return DB::transaction(function () use ($data) {
            try {
                // Hash the password if provided.
                if (isset($data['password'])) {
                    // $data['password'] = bcrypt($data['password']);
                    $data['password'] = Hash::make($data['password']);
                }

                // Handle image upload if an image is provided.
                if (isset($data['image']) && $data['image']) {
                    $uploadResult = $this->localFileService->uploadFile(
                        $data['image'],
                        $this->user_image_directory
                    );
                    $data['image'] = $uploadResult['path'];
                }

                // Create the user.
                $user = User::create($data);

                // Fire the event
                event(new UserCreated($user));

                return $user;
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function update($id, array $data): User {
        return DB::transaction(function () use ($data, $id) {
            try {
                // Hash the password if provided.
                if (isset($data['password'])) {
                    // $data['password'] = bcrypt($data['password']);
                    $data['password'] = Hash::make($data['password']);
                }

                // Find the user.
                $user = User::findOrFail($id);

                // Handle image upload if an image is provided.
                if (isset($data['image']) && $data['image']) {
                    // Delete the old image if it exists.
                    if ($user->image) {
                        $this->localFileService->deleteFile($user->image);
                    }

                    $uploadResult = $this->localFileService->uploadFile(
                        $data['image'],
                        $this->user_image_directory
                    );
                    $data['image'] = $uploadResult['path'];
                }

                // Update the user.
                $user->update($data);
                return $user;
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function delete($id): bool {
        return DB::transaction(function () use ($id) {
            try {
                // Find the user.
                $user = User::findOrFail($id);

                // Delete the user's image if it exists.
                if ($user->image) {
                    $this->localFileService->deleteFile($user->image);
                }

                // Delete the user.
                return $user->delete();
            } catch (Exception $e) {
                // DB::rollBack();
                throw $e;
            }
        });
    }

    public function getUserWithImage($id): array {
        try {
            $user = User::findOrFail($id);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'image_url' => $user->image ? url($user->image) : null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
