<?php

namespace App\Services\RolePermissionService;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Services\RolePermissionService\RolePermissionServiceInterface;

class RolePermissionService implements RolePermissionServiceInterface {

    public function getRoles(): Collection {
        return Role::with('permissions')->get();
    }

    public function getPermissions(): Collection {
        return Permission::all();
    }

    public function getUsersWithRoles(): Collection {
        return User::with('roles', 'permissions')->get();
    }

    private function getUserById(int $userId): User {
        $userInstance = User::findOrFail($userId);
        return $userInstance;
    }

    public function createPermission($data): Permission {
        $permissionInstance = Permission::firstOrCreate(['name' => $data['name']]);
        return $permissionInstance;
    }

    public function updatePermission($permissionId, $data): Permission {
        $permissionInstance = $this->getPermissionById($permissionId);
        $permissionInstance->update(['name' => $data['name']]);
        return $permissionInstance;
    }

    public function deletePermission($permissionId): bool {
        $permissionInstance = $this->getPermissionById($permissionId);
        $permissionInstance->delete();
        return true;
    }

    public function getPermissionById($permissionId): Permission {
        $permissionInstance = Permission::findOrFail($permissionId);
        return $permissionInstance;
    }

    public function createRole($data): Role {
        return DB::transaction(function () use ($data) {
            $roleInstance = Role::firstOrCreate(['name' => $data['name']]);
            if (isset($data['permissions'])) {
                $roleInstance->syncPermissions($data['permissions']);
            }
            return $roleInstance;
        });
    }

    public function updateRole($roleId, $data): Role {
        $roleInstance = $this->getRoleById($roleId);
        $roleInstance->update(['name' => $data['name']]);
        $roleInstance->syncPermissions($data['permissions'] ?? []);
        return $roleInstance;
    }

    public function deleteRole($roleId): bool {
        $roleInstance = $this->getRoleById($roleId);
        $roleInstance->delete();
        return true;
    }

    public function getRoleById($roleId): Role {
        $roleInstance = Role::findOrFail($roleId);
        return $roleInstance;
    }

    public function assignPermissionToRole(string $role, string $permission): bool {
        $roleInstance = Role::findOrCreate($role);
        $permissionInstance = Permission::findOrCreate($permission);
        $roleInstance->givePermissionTo($permissionInstance);
        return true;
    }

    public function revokePermissionFromRole(string $role, string $permission): bool {
        $roleInstance = Role::findByName($role);
        $permissionInstance = Permission::findByName($permission);
        $roleInstance->revokePermissionTo($permissionInstance);
        return true;
    }

    public function assignPermissionToUser($userId, $permission): bool {
        $userInstance = $this->getUserById($userId);
        // $permissionInstance = Permission::findByName($permission);
        $permissionInstance = Permission::findOrCreate($permission);
        $userInstance->givePermissionTo($permissionInstance);
        return true;
    }

    public function revokePermissionFromUser($userId, $permission): bool {
        $userInstance = $this->getUserById($userId);
        $permissionInstance = Permission::findByName($permission);
        $userInstance->revokePermissionTo($permissionInstance);
        return true;
    }

    public function assignRoleToUser(int $userId, string $role): bool {
        $userInstance = $this->getUserById($userId);
        // $roleInstance = Role::findByName($role);
        $roleInstance = Role::findOrCreate($role);
        $userInstance->assignRole($roleInstance);
        return true;
    }

    public function revokeRoleFromUser(int $userId, string $role): bool {
        $userInstance = $this->getUserById($userId);
        $roleInstance = Role::findByName($role);
        $userInstance->removeRole($roleInstance);
        return true;
    }

    public function checkUserPermission(int $userId, string $permission): bool {
        $userInstance = $this->getUserById($userId);
        return $userInstance->can($permission);
    }

    public function checkUserRole($userId, string $role): bool {
        $userInstance = $this->getUserById($userId);
        return $userInstance->hasRole($role);
    }

    public function authorizeAction(string $permission): bool {
        $userInstance = Auth::user();
        return $userInstance->can($permission);
    }

}
