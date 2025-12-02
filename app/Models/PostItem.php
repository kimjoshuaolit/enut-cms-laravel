<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostItem extends Model
{
    use HasFactory;

    protected $table = 'post_items';
    protected $fillable = [
        'id',
        'post_title',
        'post_description',
        'post_description2',
        'post_url',
        'post_cat',
        'post_type',
        'post_survey',
        'post_year',
        'pic_file',
        'pdf_path',
        'date_pub',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
