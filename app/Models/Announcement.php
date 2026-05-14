<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcement';
    protected $primaryKey = 'ann_id';
    public $timestamps = false;

    protected $fillable = [
        'ann_media',
        'ann_title',
        'ann_category',
        'ann_date',
        'ann_article',
    ];
}
