<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'box',
        'x',
        'y',
        'piececolor',
        'number',
        'validation',
        'uno',
        'dos',
        'tres',
        'cuatro',
        'id_room',
    ];
}
