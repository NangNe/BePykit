<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tables extends Model
{
    use HasFactory;
    protected $table = 'tables';
    protected $primaryKey = 'id_tables';
    protected $fillable = [
        'id_table',
        'stores_branch_id',
        'floor_id',
        'qr_code_id',
        'capacity',
        'number_table',
        'image_id',
        'status',
        'created_at',
        'updated_at',
    ];
    // hiden id
    protected $hidden = [
        'id',
    ];
}
