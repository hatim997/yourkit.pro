<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartContentAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['cart_content_id', 'attribute_id', 'attribut_value_id', 'quantity'];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(CartContent::class, 'cart_content_id', 'id');
    }
}
