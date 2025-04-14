<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['table', 'table_id', 'cartId', 'sessionId', 'print_positions', 'size_extra_cost', 'position_extra_cost', 'info_extra_cost', 'total_extra_cost', 'total_cost', 'is_email_checked', 'is_phone_checked', 'comment', 'is_phone_on_t-shirt', 'is_phone_on_hoodie', '	is_email_on_t-shirt', 'is_email_on_hoodie'];

    public function contents(): HasMany
    {
        return $this->hasMany(CartContent::class, 'cart_id', 'id');
    }

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class, 'table_id', 'id');
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($cart) {
            $cart->contents()->delete();
        });
    }



}
