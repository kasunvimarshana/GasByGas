<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\AuditLog;

trait Auditable {
    protected static function bootAuditable() {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $model->audit($event);
            });
        }
    }

    public function audit($event) {
        $user = Auth::user();

        AuditLog::create([
            'auditable_type' => static::class,
            'auditable_id' => $this->id,
            'event' => $event,
            'old_values' => $event === 'updated' ? $this->getOriginal() : null,
            'new_values' => $event === 'updated' ? $this->getDirty() : ($event === 'created' ? $this->getAttributes() : null),
            'user_id' => $user ? $user->id : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }

    public function auditLogs() {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
