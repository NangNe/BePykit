<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    use HasFactory;

    protected $table = 'stores';

    protected $fillable = [
        'id_stores',
        'user_id',
        'place_type_id ',
        'image_id',
        'name_store',
        'description',
        'phone_number',
        'status',
        'created_at',
        'updated_at',
    ];

}
