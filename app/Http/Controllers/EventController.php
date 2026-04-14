<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view('admin.events', compact('events'));
    }

    public function store(Request $request)
    {
        $event = new Event();
        $event->eventName = $request->input('eventName');
        $event->status = $request->input('status');
        $event->save();

        return redirect()->route('admin.events')->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        $users = User::all();
        return view('admin.events-edit', compact('event', 'users'));
    }

    public function update(Request $request, Event $event)
    {
        $event->update([
            'eventName' => $request->eventName,
            'status' => $request->status,
        ]);

        // Sync judges and sas assignments
        $event->judges()->sync($request->input('judges', []));
        $event->sas()->sync($request->input('sas', []));

        return redirect()->route('admin.events')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events')->with('success', 'Event deleted successfully!');
    }
}
