<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EcommerceAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'size_id', 'color_id', 'size_value_id', 'color_value_id', 'price', 'quantity', 'image'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function sizeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'size_value_id');
    }

    public function colorValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'color_value_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(EcomAttributeImage::class, 'ecommerce_attribute_id', 'id');
    }
}
