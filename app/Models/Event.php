<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contestant;

class Event extends Model
{
    protected $fillable =
        [
            'eventName',
            'status',
        ];

    public function contestants()
    {
        return $this->hasMany(Contestant::class);
    }

    public function judges()
    {
        return $this->belongsToMany(User::class, 'event_judge');
    }

    public function sas()
    {
        return $this->belongsToMany(User::class, 'event_sas');
    }

}
