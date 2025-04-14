<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PositionImage extends Model
{
    use HasFactory;

    public function position(): BelongsTo
    {
        return $this->belongsTo(ProductPosition::class, 'product_position_id', 'id');
    }
}
