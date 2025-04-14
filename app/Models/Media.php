<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['table_name', 'table_id', 'media_type', 'path', 'file_name'];

    /**
     * Get the user that owns the Media
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'table_id', 'id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'table_id', 'id');
    }

    /**
     * Get the user that owns the Media
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class, 'table_id', 'id');
    }

    /**
     * Boot method for the model.
     * Automatically delete the associated file from storage when a Media record is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        // When deleting a Media record, also delete the associated file from storage
        static::deleting(function ($media) {
            if ($media->path && Storage::exists($media->path)) {
                Storage::delete($media->path); // Delete the file from storage
            }
        });
    }

}
