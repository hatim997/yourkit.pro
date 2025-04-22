<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bundle extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'subcategories', 'name', 'price', 'discount_percentage', 'image', 'status', 'bundle_amount'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_bundles', 'bundle_id', 'product_id')->withPivot('quantity');
    }
    public function bundleProducts()
    {
        return $this->hasMany(ProductBundle::class, 'bundle_id');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'table_id', 'id')->where('table_name', 'bundles');
    }
}
