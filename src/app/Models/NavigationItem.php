<?php
// declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NavigationItem extends Model {
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'icon',
        'route',
        'parent_id',
        'order',
        'parameters',
        'permission',
        'types',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'metadata' => 'array',
        'parameters' => 'array',
        'types' => 'array',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions {
        $logOptions = LogOptions::defaults();
        // $logOptions->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
        return $logOptions;
    }

    public function parent(): BelongsTo {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children(): HasMany {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('children');
    }

    public function getRouteWithParameters(): string {
        $route = '#';
        if ($this->route) {
            // $parameters = json_decode($this->parameters, true);
            $parameters =  $this->parameters ?? [];
            $route = route($this->route, $parameters);
        }
        return $route;
    }
}

