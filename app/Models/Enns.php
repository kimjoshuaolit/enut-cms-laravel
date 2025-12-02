<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enns extends Model
{
    use HasFactory;

    protected $table = 'enns';
    protected $fillable = [
        'enns_id',
        'enns_title',
        'enns_description',
        'img_path',
        'created_on',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
