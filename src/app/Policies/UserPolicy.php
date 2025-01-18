<?php

namespace App\Policies;

use App\Policies\BasePolicy;
use App\Models\User;

class UserPolicy extends BasePolicy {
    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user The authenticated user
     * @return bool
     */
    public function viewAny(User $user): bool {
        return parent::viewAny($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user The authenticated user
     * @param \App\Models\User $model The user model being viewed
     * @return bool
     */
    public function view(User $user, $model): bool {
        return parent::view($user, $model);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user The authenticated user
     * @return bool
     */
    public function create(User $user): bool {
        return parent::create($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user The authenticated user
     * @param \App\Models\User $model The user model being updated
     * @return bool
     */
    public function update(User $user, $model): bool {
        return parent::update($user, $model);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user The authenticated user
     * @param \App\Models\User $model The user model being deleted
     * @return bool
     */
    public function delete(User $user, $model): bool {
        return parent::delete($user, $model);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user The authenticated user
     * @param \App\Models\User $model The user model being restored
     * @return bool
     */
    public function restore(User $user, $model): bool {
        return parent::restore($user, $model);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user The authenticated user
     * @param \App\Models\User $model The user model being permanently deleted
     * @return bool
     */
    public function forceDelete(User $user, $model): bool {
        return parent::forceDelete($user, $model);
    }
}
