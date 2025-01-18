<?php
namespace App\Services\ActivityLoggerService;

use Illuminate\Database\Eloquent\Model;
use Closure;

interface ActivityLoggerInterface {
    /**
     * Log an activity.
     *
     * @param string $logName
     * @param string $description
     * @param Model|null $model
     * @param Model|null $causer
     * @param array $properties
     * @return void
     */
    public function log(
        string $logName,
        string $description,
        ?Model $model = null,
        ?Model $causer = null,
        array $properties = []
    ): void;

    /**
     * Log a model-specific event.
     *
     * @param string $logName
     * @param string $event
     * @param Model $model
     * @param Model|null $causer
     * @return void
     */
    public function logModelEvent(
        string $logName,
        string $event,
        Model $model,
        ?Model $causer = null
    ): void;

    /**
     * Start a batch of logs.
     *
     * @return void
     */
    public function startBatch(): void;

    /**
     * End the batch of logs.
     *
     * @return void
     */
    public function endBatch(): void;

    /**
     * Execute a callback within a batch of logs.
     *
     * @param Closure $callback
     * @return mixed
     */
    public function withinBatch(Closure $callback): mixed;

    /**
     * Get the UUID of the current batch.
     *
     * @return string|null
     */
    public function getBatchUuid(): ?string;

    /**
     * Get the latest activity for a given subject.
     *
     * @param Model $subject A model instance with the `HasActivity` trait.
     * @return \Spatie\Activitylog\Models\Activity|null
     */
    public function getLatestActivity(Model $subject): ?\Spatie\Activitylog\Models\Activity;

    /**
     * Get all activities for a given subject.
     *
     * @param Model $subject A model instance with the `HasActivity` trait.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllActivities(Model $subject);
}
