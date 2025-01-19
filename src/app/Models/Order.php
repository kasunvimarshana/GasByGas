<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;

    public function relatedEntity() {
        return $this->morphTo(
            'related_entity',         // Morph Name (same as the columns related_entity_id, related_entity_type)
            'related_entity_type',    // The column that holds the class name of the related model
            'related_entity_id',      // The column that holds the ID of the related model
            'id'                      // Local key in the parent (Order) table, which is 'id'
        );
    }
}
