<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItems;

class Orders extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'id_order',
        'user_id',
        'table_id',
        'staff_id',
        'name_user',
        'phone_number',
        'description',
        'order_type',
        'total_price',
        'status',
        'created_at',
        'updated_at',
    ];

    //hiden id
    protected $hidden = [
        'id',
    ];
    // getmenu item
    public function order_items()
    {
        return $this->hasMany(OrderItems::class, 'order_id', 'id');
    }
}
