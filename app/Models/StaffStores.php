<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffStores extends Model
{
    use HasFactory;
    protected $table = 'staff_stores';
    protected $fillable = [
        'id_staff_store',
        'user_id',
        'store_id',
        'store_branch_id',
        'floor_id',
        'table_id',
        'roleID',
        'employment_type',
        'image_id',
        'wage',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];
    // hide column id
    protected $hidden = [
        'id',
    ];
}
