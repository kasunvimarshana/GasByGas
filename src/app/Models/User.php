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
// use Illuminate\Database\Eloquent\Attributes\ObservedBy;
// use App\Observers\UserObserver;

// #[ObservedBy([UserObserver::class])]
class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, LogsActivity, HasRoles;

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
        // 'company_id',
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
}
