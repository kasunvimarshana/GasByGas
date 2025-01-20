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

class StockMovement extends Model {
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quantity',
        'type',
        'reference',
        'stock_id',
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
        'type' => \App\Enums\StockMovementType::class,
    ];

    public function getActivitylogOptions(): LogOptions {
        $logOptions = LogOptions::defaults();
        // $logOptions->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
        return $logOptions;
    }

    public function stock() {
        return $this->belongsTo(\App\Models\Stock::class, 'stock_id', 'id');
    }

    public function product() {
        return $this->hasOneThrough(
            \App\Models\Product::class, // Target model
            \App\Models\Stock::class, // Intermediate model
            'id',         // Foreign key on the Stock table
            'id',         // Foreign key on the Product table
            'stock_id',   // Local key on the StockMovement table
            'product_id'  // Local key on the Stock table
        );
    }
}
