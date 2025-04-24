<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'productId', 'description', 'no_of_stock', 'category_id', 'sub_category_id', 'slug', 'mrp', 'price', 'meta_title', 'meta_description', 'meta_keywords', 'status', 'product_type','size_chart'];

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->slug = Str::slug($model->name);
    //     });

    //     static::updating(function ($model) {
    //         $model->slug = Str::slug($model->name);
    //     });
    // }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes', 'product_id', 'attribute_id')
            ->withPivot('id', 'value', 'image', 'extra_cost');
    }

    public function bundles(): BelongsToMany
    {
        return $this->belongsToMany(Bundle::class, 'product_bundles', 'product_id', 'bundle_id');
    }

    public function color(){
        return $this->attributes()->where('type', 'Color');
    }

    public function size(){
        return $this->attributes()->where('type', 'Size');
    }

    public function cartcontents(): HasMany
    {
        return $this->hasMany(CartContent::class, 'product_id', 'id');
    }

    public function ecommerce(): HasMany
    {
        return $this->hasMany(EcommerceAttribute::class, 'product_id', 'id');
    }
    public function productVolumeDiscounts(): HasMany
    {
        return $this->hasMany(ProductVolumeDiscount::class, 'product_id');
    }

}
