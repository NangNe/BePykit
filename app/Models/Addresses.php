<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'id_address',
        'street',
        'village',
        'commune',
        'district',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'id',
    ];
}
