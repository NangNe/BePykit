<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;
use App\Models\MenuItems;

class OrderItems extends Model
{
    use HasFactory;
    protected $table = 'order_items';

    protected $fillable = [
        'id_order_item',
        'order_id',
        'menu_item_id',
        'quantity',
        'price',
        'total_price',
        'status',
    ];

    protected $hidden = [
        'id',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    public function menu_item()
    {
        return $this->belongsTo(MenuItems::class, 'menu_item_id', 'id');
    }


}
