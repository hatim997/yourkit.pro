<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status','type','url','url_name'];

    /**
     * Get the user associated with the Banner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function media(): HasOne
    {
        return $this->hasOne(Media::class, 'table_id', 'id')->where('table_name', 'banners');
    }
}
