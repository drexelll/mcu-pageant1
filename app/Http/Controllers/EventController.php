<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Contestant;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view('admin.events', compact('events'));
    }

    public function create()
    {
        $users = User::all();
        $contestants = Contestant::all();
        return view('admin.events-create', compact('users', 'contestants'));
    }

    public function store(Request $request)
    {
        $event = new Event();
        $event->eventName = $request->input('eventName');
        $event->status = $request->input('status');
        $event->save();

        // Attach judges, SAS, contestants if provided
        $event->judges()->sync($request->input('judges', []));
        $event->sas()->sync($request->input('sas', []));
        $event->contestants()->sync($request->input('contestants', []));

        return redirect()->route('admin.events')
            ->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        $users = User::all();
        $contestants = Contestant::all();
        return view('admin.events-edit', compact('event', 'users', 'contestants'));
    }

    public function update(Request $request, Event $event)
    {
        $event->eventName = $request->input('eventName');
        $event->status = $request->input('status');
        $event->save();

        return redirect()->route('admin.events')
            ->with('success', 'Event updated successfully!');
    }

    public function assign(Request $request, Event $event, $type)
    {
        switch ($type) {
            case 'judge':
                $event->judges()->sync($request->input('judges', []));
                break;

            case 'sas':
                $event->sas()->sync($request->input('sas', []));
                break;

            case 'contestant':
                $event->contestants()->sync($request->input('contestants', []));
                break;

            default:
                return redirect()->route('admin.events.edit', $event->id)
                    ->with('error', 'Invalid assignment type.');
        }

        return redirect()->route('admin.events.edit', $event->id)
            ->with('success', ucfirst($type) . ' assigned successfully!');
    }

    public function unassign(Event $event, $type, $id)
    {
        if ($type === 'judge') {
            $event->judges()->detach($id);
        } elseif ($type === 'sas') {
            $event->sas()->detach($id);
        } elseif ($type === 'contestant') {
            $event->contestants()->detach($id);
        }

        return redirect()->route('admin.events.edit', $event->id)
            ->with('success', ucfirst($type) . ' removed successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events')->with('success', 'Event deleted successfully!');
    }
}
