<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floors extends Model
{
    use HasFactory;
    protected $table = 'floors';
    protected $primaryKey = 'id_floors';
    protected $fillable = [
        'id_floors',
        'store_id',
        'store_branch_id',
        'name_floors',
        'floor_number',
        'description',
        'image_id',
        'status',
    ];
    public $incrementing = false;
    // hiden id
    protected $hidden = [
        'id',
    ];
    // get table
    public function tables()
    {
        return $this->hasMany(Tables::class, 'floor_id', 'id');
    }
}
