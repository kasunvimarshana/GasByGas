<?php

// Define permissions for user role
$userPermissions = [
    'dashboard',

    'carts',
    'carts.index',
    'carts.create',
    'carts.store',
    'carts.edit',
    'carts.update',
    'carts.destroy',
    'carts.show',
    'carts.checkout',

    'orders',
    'orders.index',
    'orders.store',
    'orders.update',
    'orders.destroy',
    'orders.show',
];

// Define permissions for admin role
$adminPermissions = [
    'dashboard',

    'users',
    'users.edit',
    'users.update',
    'users.destroy',
    'users.show',
    'users.index-for-company',
    'users.create-for-company',
    'users.store-for-company',

    'products',
    'products.index',
    'products.create',
    'products.store',
    'products.edit',
    'products.update',
    'products.destroy',
    'products.show',

    'stocks',
    'stocks.index',
    'stocks.create',
    'stocks.store',
    'stocks.edit',
    'stocks.update',
    'stocks.destroy',
    'stocks.show',

    'stock-movements',
    'stock-movements.index',
    'stock-movements.store',
    'stock-movements.update',
    'stock-movements.destroy',
    'stock-movements.show',

    'carts',
    'carts.index',
    'carts.create',
    'carts.store',
    'carts.edit',
    'carts.update',
    'carts.destroy',
    'carts.show',
    'carts.checkout',

    'orders',
    'orders.index',
    'orders.store',
    'orders.update',
    'orders.destroy',
    'orders.show',
    'orders.index-for-company',
];

// Define permissions for super admin role
$superAdminPermissions = [
    // // User Management Permissions
    // 'user.manage',        // General user management permission
    // 'user.create',        // Permission to create a user
    // 'user.view',          // Permission to view a specific user
    // 'user.view_any',      // Permission to view any user
    // 'user.update',        // Permission to update a user
    // 'user.delete',        // Permission to delete a user
    // 'user.force_delete',  // Permission to force delete a user
    // 'user.restore',       // Permission to restore a deleted user
    'dashboard',
    'users',
    'users.index',
    'users.create',
    'users.store',
    'users.edit',
    'users.update',
    'users.destroy',
    'users.show',
    'users.index-for-company',
    'users.create-for-company',
    'users.store-for-company',

    'companies',
    'companies.index',
    'companies.create',
    'companies.store',
    'companies.edit',
    'companies.update',
    'companies.destroy',
    'companies.show',

    'products',
    'products.index',
    'products.create',
    'products.store',
    'products.edit',
    'products.update',
    'products.destroy',
    'products.show',

    'stocks',
    'stocks.index',
    'stocks.create',
    'stocks.store',
    'stocks.edit',
    'stocks.update',
    'stocks.destroy',
    'stocks.show',

    'stock-movements',
    'stock-movements.index',
    'stock-movements.store',
    'stock-movements.update',
    'stock-movements.destroy',
    'stock-movements.show',

    'carts',
    'carts.index',
    'carts.create',
    'carts.store',
    'carts.edit',
    'carts.update',
    'carts.destroy',
    'carts.show',
    'carts.checkout',

    'orders',
    'orders.index',
    'orders.store',
    'orders.update',
    'orders.destroy',
    'orders.show',
    'orders.index-for-company',
];

// Configuration for role names and permissions
return [
    // Display names for roles
    'roles' => [
        'user' => env('USER_ROLE', 'Super Admin'),
        'admin' => env('ADMIN_ROLE', 'Super Admin'),
        'super_admin' => env('SUPER_ADMIN_ROLE', 'Super Admin'),
    ],

    // Role-specific permissions
    'role_permissions' => [
        'user' => $userPermissions,
        'admin' => $adminPermissions,
        'super_admin' => array_merge($userPermissions, $adminPermissions, $superAdminPermissions),
    ],

    // Consolidated permissions for the system
    'all_permissions' => array_merge($userPermissions, $adminPermissions, $superAdminPermissions),
];
