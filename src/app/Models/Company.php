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

class Company extends Model {
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'description',
        'address',
        'phone',
        'image',
        'type',
        'is_active',
        'parent_id',
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

    public function users() {
        return $this->belongsToMany(
            \App\Models\User::class,    // The related User model
            'company_users',            // The pivot table
            'company_id',               // Foreign key for the company in the pivot table
            'user_id'                   // Foreign key for the user in the pivot table
        )
        // ->withPivot()
        ->using(\App\Models\CompanyUser::class)
        ->withTimestamps();
    }

    public function admins() {
        return $this->relatedUsers(true);
    }

    private function relatedUsers(bool $isAdmin = false) {
        return $this->belongsToMany(
            \App\Models\User::class,         // Related User model
            'company_users',                 // Pivot table
            'company_id',                    // Foreign key for the company in the pivot table
            'user_id'                        // Foreign key for the user in the pivot table
        )
        ->wherePivot('is_company_admin', $isAdmin) // Filter by admin status
        ->using(\App\Models\CompanyUser::class) // Use the custom pivot class
        ->withTimestamps();                     // Include timestamps
    }

    public function isAdmin(int $userId): bool {
        // return $this->users()->where('id', $userId)->wherePivot('is_company_admin', true)->exists();
        return $this->admins()->where('id', $userId)->exists();
    }

    public function products() {
        return $this->belongsToMany(
            \App\Models\Product::class, // The related Product model
            'company_products',         // The pivot table
            'company_id',               // Foreign key for the company in the pivot table
            'product_id'                // Foreign key for the product in the pivot table
        )
        // ->withPivot()
        ->using(\App\Models\CompanyProduct::class)
        ->withTimestamps();
    }

    public function orders() {
        return $this->hasMany(\App\Models\Order::class, 'company_id', 'id');
    }

    public function purchases() {
        return $this->morphMany(
            \App\Models\Order::class,   // The related model class (Order)
            'related_entity',           // The morph type column (related_entity_type)
            'related_entity_type',      // The type column in the order table
            'related_entity_id',        // The ID column in the order table
            'id'                        // Local key in the related model
        );
    }

    public function stocks() {
        return $this->hasMany(\App\Models\Stock::class, 'company_id', 'id');
    }

    public function carts() {
        return $this->morphMany(
            \App\Models\Cart::class,    // The related model class (Cart)
            'related_entity',           // The morph type column (related_entity_type)
            'related_entity_type',      // The type column in the cart table
            'related_entity_id',        // The ID column in the cart table
            'id'                        // Local key in the related model
        );
    }

}
