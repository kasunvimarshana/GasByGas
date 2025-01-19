<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyProduct extends Pivot {
    protected $table = 'company_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'product_id',
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

    public function product(){
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }
}
