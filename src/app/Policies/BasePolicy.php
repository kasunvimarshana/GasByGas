<?php

namespace App\Policies;

// use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User as AuthUser;

class BasePolicy {
    // use HandlesAuthorization;

    /**
     * Check if the user has a specific permission.
     *
     * @param  \App\Models\User  $authUser
     * @param  string  $permission
     * @return bool
     */
    protected function hasPermission(AuthUser $authUser, string $permission): bool {
        return $authUser->can($permission);
    }

    /**
     * Check if the user is the owner of the resource.
     *
     * @param  \App\Models\User  $authUser
     * @param  int  $resourceUserId
     * @return bool
     */
    protected function isOwner(AuthUser $authUser, int $resourceUserId): bool {
        // Dynamically check ownership for resources with a 'Resource User Id' field
        return $authUser->id === $resourceUserId;
    }

    /**
     * Generic function to check if the user can perform CRUD actions on the resource.
     *
     * @param  \App\Models\User  $authUser
     * @param  string  $action
     * @param  mixed  $resource
     * @return bool
     */
    protected function canPerformAction(AuthUser $authUser, string $action, $resource): bool {
        return $this->hasPermission($authUser, "{$action} resource") || $this->isOwner($authUser, $resource->user_id);
    }

    /**
     * Check if the user can view any resource of a given type (e.g., Post, Comment).
     *
     * @param  \App\Models\User  $authUser
     * @return bool
     */
    public function viewAny(AuthUser $authUser): bool {
        return $this->hasPermission($authUser, 'view any resource');
    }

    /**
     * Check if the user can create a resource.
     *
     * @param  \App\Models\User  $authUser
     * @return bool
     */
    public function create(AuthUser $authUser): bool {
        return $this->hasPermission($authUser, 'create resource');
    }

    /**
     * Check if the user can view the specific resource.
     *
     * @param  \App\Models\User  $authUser
     * @param  mixed  $resource
     * @return bool
     */
    public function view(AuthUser $authUser, $resource): bool {
        return $this->canPerformAction($authUser, 'view', $resource);
    }

    /**
     * Check if the user can update the specific resource.
     *
     * @param  \App\Models\User  $authUser
     * @param  mixed  $resource
     * @return bool
     */
    public function update(AuthUser $authUser, $resource): bool {
        return $this->canPerformAction($authUser, 'update', $resource);
    }

    /**
     * Check if the user can delete the specific resource.
     *
     * @param  \App\Models\User  $authUser
     * @param  mixed  $resource
     * @return bool
     */
    public function delete(AuthUser $authUser, $resource): bool {
        return $this->canPerformAction($authUser, 'delete', $resource);
    }

    /**
     * Check if the user can force delete the specific resource.
     *
     * @param  \App\Models\User  $authUser
     * @param  mixed  $resource
     * @return bool
     */
    protected function forceDelete(AuthUser $authUser, $resource): bool {
        return $this->canPerformAction($authUser, 'force delete', $resource);
    }

    /**
     * Check if the user can restore the specific deleted resource.
     *
     * @param  \App\Models\User  $authUser
     * @param  mixed  $resource
     * @return bool
     */
    protected function restore(AuthUser $authUser, $resource): bool {
        return $this->canPerformAction($authUser, 'restore', $resource);
    }
}
