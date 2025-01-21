<?php

// declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NotificationChannels\WebPush\HasPushSubscriptions;
// use Illuminate\Database\Eloquent\Attributes\ObservedBy;
// use App\Observers\UserObserver;

// #[ObservedBy([UserObserver::class])]
class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, LogsActivity, HasRoles, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'name',
        'email',
        'username',
        'password',
        'description',
        'address',
        'phone',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getActivitylogOptions(): LogOptions {
        $logOptions = LogOptions::defaults();
        // $logOptions->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
        return $logOptions;
    }

    // public function setPasswordAttribute($value): void {
    //     $this->attributes['password'] = Hash::make($value);
    // }

    // protected function password(): Attribute {
    //     return Attribute::make(
    //         set: fn ($value) => bcrypt($value)
    //     );
    // }

    // public function companies() {
    //     return $this->belongsToMany(
    //         \App\Models\Company::class,  // The related Company model
    //         'company_users',            // The pivot table
    //         'user_id',                  // Foreign key for the user in the pivot table
    //         'company_id'                // Foreign key for the company in the pivot table
    //     )
    //     // ->withPivot()
    //     ->using(\App\Models\CompanyUser::class)
    //     ->withTimestamps();
    // }

    public function company() {
        return $this->hasOneThrough(
            \App\Models\Company::class,  // The related model (Company)
            \App\Models\CompanyUser::class,  // The pivot model (CompanyUser)
            'user_id',  // Foreign key on the pivot model (CompanyUser) for User
            'id',       // Foreign key on the related model (Company) for CompanyUser
            'id',       // Local key on the User model
            'company_id'  // Foreign key on the pivot model for the related model (CompanyUser)
        );
    }

    private function relatedCompanies(bool $isAdmin = false) {
        return $this->belongsToMany(
            \App\Models\Company::class,   // The related Company model
            'company_users',              // The pivot table
            'user_id',                    // Foreign key for the user in the pivot table
            'company_id'                  // Foreign key for the company in the pivot table
        )
        ->wherePivot('is_company_admin', $isAdmin) // Filter by admin status
        ->using(\App\Models\CompanyUser::class)    // Use the custom pivot class
        ->withTimestamps();                        // Include timestamps
    }

    public function adminOfCompanies() {
        return $this->relatedCompanies(true);
    }

    public function isCompanyAdmin(int $companyId): bool {
        // return $this->companies()->where('id', $companyId)->wherePivot('is_company_admin', true)->exists();
        return $this->adminOfCompanies()->where('id', $companyId)->exists();
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
