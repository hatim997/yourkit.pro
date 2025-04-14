<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $table = "email_templates";

    protected $fillable = [
        'email_type',
        'subject',
        'content',
        'status',
    ];
    public static array $statusCodes = ['<span class="badge bg-inverse-danger">Disabled</span>','<span class="badge bg-inverse-success">Active</span>'];

}
