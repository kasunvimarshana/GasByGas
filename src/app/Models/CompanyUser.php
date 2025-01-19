<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;


class CompanyUser extends Pivot {
    protected $table = 'company_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'user_id',
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

    public $timestamps = true;

    public function company() {
        return $this->belongsTo(\App\Models\Company::class, 'company_id', 'id');
    }

    public function user(){
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
