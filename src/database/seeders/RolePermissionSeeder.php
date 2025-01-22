<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void {
        // $permissions = $this->getPermissions();
        $roles = $this->getRoles();

        foreach ($roles as $roleKey => $roleValue) {
            $roleInstance = $this->createRole($roleValue);
            $this->command->info('Role : ' . $roleValue);
            $rolePermissions = config("roles_and_permissions.role_permissions.{$roleKey}", []);
            foreach ($rolePermissions as $permissionKey => $permissionValue) {
                $permissionInstance = $this->createPermission($permissionValue);
                $this->command->info('Permission : ' . $permissionValue);
                $roleInstance->givePermissionTo($permissionInstance);
            }
        }

        $this->command->info('RolePermission seeded successfully!');
    }

    /**
     * Get the predefined permissions for user management.
     *
     * @return array
     */
    private function getPermissions(): array {
        $permissions = config('roles_and_permissions.all_permissions', []);
        return $permissions;
    }

    /**
     * Get the predefined roles for user management.
     *
     * @return array
     */
    private function getRoles(): array {
        $roles = config('roles_and_permissions.roles', []);
        return $roles;
    }

    /**
     * Create a permission if it doesn't exist and return the permission instance.
     *
     * @param string $permissionName
     * @return \Spatie\Permission\Models\Permission
     */
    private function createPermission(string $permissionName): Permission {
        return Permission::firstOrCreate(['name' => $permissionName]);
    }

    /**
     * Create a role if it doesn't exist and return the role instance.
     *
     * @param string $roleName
     * @return \Spatie\Permission\Models\Role
     */
    private function createRole(string $roleName): Role {
        return Role::firstOrCreate(['name' => $roleName]);
    }

    /**
     * Assign permissions to a role.
     *
     * @param \Spatie\Permission\Models\Role $role
     * @param array $permissions
     * @return void
     */
    private function assignPermissionsToRole(Role $role, array $permissions): void {
        // $role->givePermissionTo(Permission::all());
        // Use syncPermissions to ensure only the required permissions are assigned
        $role->syncPermissions($permissions);
    }

    /**
     * Assign a role to a user by their ID.
     *
     * @param int $userId
     * @param string $roleName
     * @return void
     */
    private function assignRoleToUser(int $userId, string $roleName): void {
        $user = User::find($userId);
        if ($user) {
            $user->assignRole($roleName);
        }
    }
}
