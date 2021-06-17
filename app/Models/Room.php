<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'game',
        'password',
        'ready',
        'status',
        'id_user1',
        'id_user2',
        'id_user3',
        'id_user4',
    ];
}
