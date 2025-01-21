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
use Illuminate\Database\Eloquent\Builder;

class Cart extends Model {
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quantity',
        'product_id',
        'related_entity_id',
        'related_entity_type',
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
    ];

    public function getActivitylogOptions(): LogOptions {
        $logOptions = LogOptions::defaults();
        $logOptions = $logOptions->logOnly(['quantity', 'product_id', 'related_entity_id', 'related_entity_type']);
        // $logOptions->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
        return $logOptions;
    }

    public function product(): BelongsTo {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }

    public function relatedEntity() {
        return $this->morphTo(
            'related_entity',         // Morph Name (same as the columns related_entity_id, related_entity_type)
            'related_entity_type',    // The column that holds the class name of the related model
            'related_entity_id',      // The column that holds the ID of the related model
            'id'                      // Local key in the parent (Cart) table, which is 'id'
        );
    }

    public function scopeWithRelatedEntityType(Builder $query, string $relatedType): Builder {
        return $query->where('related_entity_type', $relatedType);
    }

    public function relatedUsers() {
        return $this->morphToMany(
            \App\Models\User::class,
            'related_entity',
            'related_entity_type',
            'related_entity_id',
            'id'
        );
    }

    public function relatedCompanies() {
        return $this->morphToMany(
            \App\Models\Company::class,
            'related_entity',
            'related_entity_type',
            'related_entity_id',
            'id'
        );
    }

    public function scopeWithCompany(Builder $query): Builder {
        return $query->withRelatedEntityType(\App\Models\Company::class);
    }

    public function scopeWithUser(Builder $query): Builder {
        return $query->withRelatedEntityType(\App\Models\User::class);
    }
}
