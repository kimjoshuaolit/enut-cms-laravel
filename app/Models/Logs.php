<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Logs extends Model
{
    use HasFactory;

    protected $table = 'logs';
    protected $fillable = [
        'id',
        'accid',
        'description',
        'datetime',

    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function scopeLogins($query)
    {
        return $query->where('description', 'like', 'Login to his/her account%');
    }
}
