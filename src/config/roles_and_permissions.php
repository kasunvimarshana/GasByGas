<?php

// Define permissions for guest role (empty for now)
$guestPermissions = [];

// Define permissions for user role (empty for now)
$userPermissions = [];

// Define permissions for super admin role
$superAdminPermissions = [
    // User Management Permissions
    'user.manage',        // General user management permission
    'user.create',        // Permission to create a user
    'user.view',          // Permission to view a specific user
    'user.view_any',      // Permission to view any user
    'user.update',        // Permission to update a user
    'user.delete',        // Permission to delete a user
    'user.force_delete',  // Permission to force delete a user
    'user.restore',       // Permission to restore a deleted user
];

// Configuration for role names and permissions
return [
    // Super Admin role name (uses environment variable or defaults to 'Super Admin')
    'super_admin_role' => env('SUPER_ADMIN_ROLE', 'Super Admin'),

    // Display names for roles
    'roles' => [
        'guest' => 'Guest',
        'user' => 'User',
        'super_admin' => env('SUPER_ADMIN_ROLE', 'Super Admin'),
    ],

    // Role-specific permissions
    'role_permissions' => [
        'guest' => $guestPermissions,
        'user' => $userPermissions,
        'super_admin' => array_merge($guestPermissions, $userPermissions, $superAdminPermissions),
    ],

    // Consolidated permissions for the system
    'all_permissions' => array_merge($guestPermissions, $userPermissions, $superAdminPermissions),
];
