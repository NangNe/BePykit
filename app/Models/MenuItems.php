<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItems extends Model
{
    use HasFactory;
    protected $table = 'menu_items';
    protected $fillable = [
        'id_menu_item',
        'menu_type_id',
        'image_id',
        'name_menu_item',
        'description',
        'price',
        'status',
        'currency',
        'unit',
        'created_at',
        'updated_at',
    ];
    protected $hidden = [
        'id',
    ];

    public function images()
    {
        return $this->hasMany(Images::class, 'image_query_id', 'id_menu_item');
    }

    public function promotion()
    {
        return $this->hasMany(MenuItemPromotions::class, 'menu_item_id', 'id')->orderBy('created_at', 'desc');
    }
  
}
