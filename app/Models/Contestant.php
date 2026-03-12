<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contestant extends Model
{
    protected $fillable =
    [
        'number',
        'name',
        'course',
        'photo',
    ];
}
