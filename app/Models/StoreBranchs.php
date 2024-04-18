<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StoreBranchs extends Model
{
    use HasFactory;
    protected $table = 'store_branchs';
    protected $fillable = [
        'id_store_branch',
        'store_id',
        'place_type_id',
        'address_id',
        'image_id',
        'name_store_branch',
        'description',
        'phone_number',
        'status',
        'open_time',
        'close_time',
        'created_at',
        'updated_at',
    ];
    // hide column id
    protected $hidden = [
        'id',
    ];
}
