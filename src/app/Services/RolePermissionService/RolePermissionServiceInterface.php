<?php

namespace App\Services\RolePermissionService;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

interface RolePermissionServiceInterface {
    // Role Management
    public function getRoles(): Collection;
    public function createRole(array $data): Role;
    public function updateRole(int $roleId, array $data): Role;
    public function deleteRole(int $roleId): bool;
    public function getRoleById(int $roleId): Role;
    public function assignPermissionToRole(string $role, string $permission): bool;
    public function revokePermissionFromRole(string $role, string $permission): bool;

    // Permission Management
    public function getPermissions(): Collection;
    public function createPermission(array $data): Permission;
    public function updatePermission(int $permissionId, array $data): Permission;
    public function deletePermission(int $permissionId): bool;
    public function getPermissionById(int $permissionId): Permission;

    // User Management
    public function getUsersWithRoles(): Collection;
    public function assignPermissionToUser(int $userId, string $permission): bool;
    public function revokePermissionFromUser(int $userId, string $permission): bool;
    public function assignRoleToUser(int $userId, string $role): bool;
    public function revokeRoleFromUser(int $userId, string $role): bool;

    // Authorization
    public function checkUserPermission(int $userId, string $permission): bool;
    public function checkUserRole(int $userId, string $role): bool;
    public function authorizeAction(string $permission): bool;
}
