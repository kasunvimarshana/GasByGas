<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class BaseObserver {
    /**
     * Handle the "created" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function created(Model $model) {
        // Default behavior for created event
        // Override this in the specific observer if needed
    }

    /**
     * Handle the "updated" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function updated(Model $model) {
        // Default behavior for updated event
        // Override this in the specific observer if needed
    }

    /**
     * Handle the "deleted" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function deleted(Model $model) {
        // Default behavior for deleted event
        // Override this in the specific observer if needed
    }

    /**
     * Handle the "restored" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function restored(Model $model) {
        // Default behavior for restored event
        // Override this in the specific observer if needed
    }

    /**
     * Handle the "force deleted" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function forceDeleted(Model $model) {
        // Default behavior for force deleted event
        // Override this in the specific observer if needed
    }

    /**
     * Handle the "saving" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function saving(Model $model) {
        // Default behavior for saving event
        // Override this in the specific observer if needed
    }

    /**
     * Handle the "saved" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function saved(Model $model) {
        // Default behavior for saved event
        // Override this in the specific observer if needed
    }

    /**
     * Handle the "retrieved" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function retrieved(Model $model) {
        // Default behavior for retrieved event
        // Override this in the specific observer if needed
    }

    /**
     * Handle the "deleting" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function deleting(Model $model) {
        // Default behavior for deleting event
        // Override this in the specific observer if needed
    }

    /**
     * Handle the "restoring" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function restoring(Model $model) {
        // Default behavior for restoring event
        // Override this in the specific observer if needed
    }
}
