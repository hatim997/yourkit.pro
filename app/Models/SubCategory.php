<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'description', 'slug','status'];


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

    /**
     * Get the user that owns the SubCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Get all of the comments for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'sub_category_id', 'id');
    }

    public function media(): HasOne
    {
        return $this->hasOne(Media::class, 'table_id', 'id')->where('table_name', 'sub_categories');
    }

    public function productposition(): HasMany
    {
        return $this->hasMany(ProductPosition::class, 'sub_category_id', 'id');
    }
}
