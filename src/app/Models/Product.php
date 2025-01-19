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

class Product extends Model {
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sku',
        'price',
        'image',
        'color',
        'description',
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
        // $logOptions->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
        return $logOptions;
    }

    public function companies() {
        return $this->belongsToMany(
            \App\Models\Company::class, // The related Product model
            'company_products',         // The pivot table
            'product_id',               // Foreign key for the product in the pivot table
            'company_id'                // Foreign key for the company in the pivot table
        )
        // ->withPivot()
        ->using(\App\Models\CompanyProduct::class)
        ->withTimestamps();
    }

}
