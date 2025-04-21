<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'dob', 'location', 'address'];

    /**
     * Get the user associated with the UserDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
