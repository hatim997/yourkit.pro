<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcomAttributeImage extends Model
{
    use HasFactory;

    protected $fillable = ['ecommerce_attribute_id', 'image'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(EcommerceAttribute::class, 'ecommerce_attribute_id', 'id');
    }
}
