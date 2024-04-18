<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Promotions extends Model
{
    use HasFactory;
    protected $table = 'promotions';
    protected $filltable = [
        'id_promotion',
        'stores_branch_id',
        'id_image',
        'name_promotion',
        'description_promotion',
        'discount_promotion',
        'start_day_promotion',
        'end_day_promotion',
        'status', 
        'created_at',
        'updated_at',
    ];
    protected $hidden = [
        'id'
    ];


    public function images()
    {
        return $this->hasMany(Images::class, 'image_query_id', 'id_promotion');
    }

    public function promotion()
    {
        return $this->hasMany(Promotions::class, 'id_promotion', 'id')->orderBy('created_at', 'desc');
    }
}
