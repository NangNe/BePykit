<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuTypes extends Model
{
    use HasFactory;
    protected $table = 'menu_types';
    protected $fillable = [
        'id_menu_type',
        'stores_branch_id',
        'image_id',
        'name_menu_type',
        'description',
        'status',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [
        'id'
    ];
}
