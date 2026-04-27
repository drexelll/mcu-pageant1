<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contestant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
        [
            'number',
            'name',
            'course',
            'photo',
            'event_id',
        ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'contestant_event');
    }

}
