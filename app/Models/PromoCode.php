<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'discount_percentage', 'valid_from', 'valid_until', 'usage_limit', 'used_count', 'status'
    ];

    public function isValid()
    {
        $today = now();
        return $this->status === 1 &&
               $this->valid_from <= $today &&
               $this->valid_until >= $today &&
               $this->used_count < $this->usage_limit;
    }

    // Increment the used count
    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
