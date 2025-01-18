<?php
namespace App\Services\ActivityLoggerService;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Facades\LogBatch;
// use Spatie\Activitylog\Facades\Activity;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Facades\CauserResolver;
use Closure;
use App\Services\ActivityLoggerService\ActivityLoggerInterface;

class ActivityLoggerService implements ActivityLoggerInterface {
    public function log(
        string $logName,
        string $description,
        ?Model $model = null,
        ?Model $causer = null,
        array $properties = []
    ): void {
        // Override the causer if provided
        if ($causer) {
            CauserResolver::setCauser($causer);
        }
        // Log the activity
        $activity = activity($logName)
            // ->causedBy($causer)
            ->withProperties($properties);

        if ($model) {
            $activity->performedOn($model);
        }

        $activity->log($description);

        // Reset the causer after logging
        CauserResolver::setCauser(null);
    }

    public function logModelEvent(
        string $logName,
        string $event,
        Model $model,
        ?Model $causer = null
    ): void {
        $properties = [
            'attributes' => $model->getAttributes(),
            'original' => $model->getOriginal(),
        ];

        $this->log($logName,
                    "{$event} event for " . class_basename($model),
                    $model,
                    $causer,
                    $properties);
    }

    public function startBatch(): void
    {
        LogBatch::startBatch();
    }

    public function endBatch(): void
    {
        LogBatch::endBatch();
    }

    public function withinBatch(Closure $callback): mixed
    {
        return LogBatch::withinBatch($callback);
    }

    public function getBatchUuid(): ?string
    {
        return LogBatch::getUuid();
    }

    public function getLatestActivity(Model $subject): ?Activity
    {
        if (!method_exists($subject, 'activities')) {
            throw new \InvalidArgumentException(
                'The given subject must use the HasActivity trait to access activities.'
            );
        }

        return $subject->activities()->latest()->first();
    }

    public function getAllActivities(Model $subject)
    {
        if (!method_exists($subject, 'activities')) {
            throw new \InvalidArgumentException(
                'The given subject must use the HasActivity trait to access activities.'
            );
        }

        return $subject->activities()->get();
    }
}
