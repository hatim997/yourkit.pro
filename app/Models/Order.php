<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['orderID', 'user_id', 'printable_logo', 'amount', 'final_amount', 'billing_name', 'company_name', 'billing_email', 'billing_mobile', 'country', 'address', 'town', 'pincode','shipping_name',
                'shipping_company',
                'shipping_email',
                'shipping_mobile',
                'shipping_country',
                'shipping_address',
                'shipping_town',
                'shipping_postcode',
                'shipping',
                'taxable_amount',
                'comment'
            ];

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function logodisplay(): HasMany{
        return $this->hasMany(LogoDisplay::class, 'order_id', 'id');
    }

    public function tax(): HasMany
    {
        return $this->hasMany(OrderTax::class, 'order_id', 'id');
    }
}
