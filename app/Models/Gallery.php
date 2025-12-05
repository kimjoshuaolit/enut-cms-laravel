<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';
    protected $fillable = [
        'id',
        'title',
        'area',
        'page_no',
        'cat_title',
        'cat_year',
        'file_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
