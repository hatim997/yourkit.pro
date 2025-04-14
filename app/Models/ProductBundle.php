<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ProductBundle extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'bundle_id', 'quantity'];

     /**
     * Get the user that owns the Bundle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class, 'bundle_id', 'id');
    }

     /**
     * Get the user that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
