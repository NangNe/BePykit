<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $fillable = [
        'image_id',
        'image_query_id',
        'image_type',
        'image_url',
        'image_path',
        'image_name',
        'image_extension',
        'image_width',
        'image_height',
        'image_mime_type',
        'created_at',
        'updated_at',
    ];
    // hide column id
    protected $hidden = [
        'id',
    ];
}
