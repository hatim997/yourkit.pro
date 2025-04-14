<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Page extends Model
{
    use HasFactory;

    /**
     * Get the user associated with the Page
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    protected $fillable = ['name', 'status', 'slug'];
    public function content(): HasOne
    {
        return $this->hasOne(PageContent::class, 'page_id', 'id');
    }
}
