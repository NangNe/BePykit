<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceTypes extends Model
{
    use HasFactory;
    protected $table = 'place_types';
    protected $primaryKey = 'id_place_type';
    protected $fillable = [
        'id_place_type',
        'id_icon_place_type',
        'name_place_type',
        'status',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [
        'id'
    ];
}
