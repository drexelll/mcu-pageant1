<?php

namespace App\Http\Controllers\sas;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Contestant;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view('sas.events');
    }
}
