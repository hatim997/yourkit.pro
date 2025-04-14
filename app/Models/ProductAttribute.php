<?php

namespace App\Models;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'product_id', 'attribute_id', 'status', 'image', 'extra_cost'];

    /**
     * Get the user that owns the ProductAttribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attributes::class, 'attribute_id', 'id');
    }


    public function media()
    {
        return $this->hasOne(Media::class, 'table_id', 'id')->where('table_name', 'product_attributes');
    }


/**
     * Boot method for the model.
     * Automatically delete related media when a ProductAttribute is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        // When deleting a ProductAttribute, delete the related media records
        static::deleting(function ($productAttribute) {
            if ($productAttribute->image && Storage::exists($productAttribute->image)) {
                Storage::delete($productAttribute->image); // Delete the file from storage
            }
        });
    }
}
