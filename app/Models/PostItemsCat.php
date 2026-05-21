<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostItemsCat extends Model
{
    protected $table = 'post_items_cats';
    protected $fillable = [
        'cat_name',
        'sub_category',
        'value',
    ];
}
