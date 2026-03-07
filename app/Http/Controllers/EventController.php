<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index()
    {
        $events = Event::latest()->get();

        return view('events.index',compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'nullable|string',
            'event_type' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:Upcoming,Completed,Cancelled'
        ]);

        $eventData = $request->all();
        $eventData['created_by'] = auth()->id() ?? 1; // Default to user 1 if not authenticated
        
        Event::create($eventData);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully');
    }

    public function show($id)
    {

        $event = Event::findOrFail($id);

        return view('events.show',compact('event'));

    }

    public function edit($id)
    {

        $event = Event::findOrFail($id);

        return view('events.edit',compact('event'));

    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'nullable|string',
            'event_type' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:Upcoming,Completed,Cancelled'
        ]);

        $event->update($request->all());

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully');
    }

    public function destroy($id)
    {

        $event = Event::findOrFail($id);

        $event->delete();

        return redirect()->route('events.index');

    }

}