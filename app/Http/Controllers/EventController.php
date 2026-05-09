<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('search')) {
            $query->where('event_name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->latest()->paginate(20);

        $this->logActivity(
            'Viewed Event List',
            'Outreach Event',
            'Staff viewed the event list',
            ['search' => $request->search, 'status_filter' => $request->status]
        );

        return view('events.index', compact('events'));
    }

    public function create()
    {
        $this->logActivity(
            'Viewed Event Create Form',
            'Outreach Event',
            'Staff opened the event creation form'
        );

        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location'   => 'nullable|string',
            'event_type' => 'nullable|string',
            'description' => 'nullable|string',
            'status'     => 'nullable|in:Upcoming,Completed,Cancelled',
        ]);

        // Check for duplicate event (same name and date)
        $existingEvent = Event::where('event_name', $request->event_name)
            ->where('event_date', $request->event_date)
            ->first();

        if ($existingEvent) {
            return back()->withErrors(['duplicate' => 'An event with this name and date already exists.'])->withInput();
        }

        $eventData               = $request->all();
        $eventData['created_by'] = auth()->id() ?? 1;

        $event = Event::create($eventData);

        $this->logActivity(
            'Created Event',
            'Outreach Event',
            'Staff created a new event: ' . $event->event_name,
            ['event_id' => $event->getKey(), 'event_name' => $event->event_name, 'event_date' => $event->event_date, 'status' => $event->status]
        );

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        $this->logActivity(
            'Viewed Event Details',
            'Outreach Event',
            'Staff viewed details for event: ' . $event->event_name,
            ['event_id' => $id]
        );

        return view('events.show', compact('Outreach Event'));
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        $this->logActivity(
            'Viewed Event Edit Form',
            'Outreach Event',
            'Staff opened the edit form for event: ' . $event->event_name,
            ['event_id' => $id]
        );

        return view('events.edit', compact('Outreach Event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'event_name'  => 'required|string|max:255',
            'event_date'  => 'required|date',
            'location'    => 'nullable|string',
            'event_type'  => 'nullable|string',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:Upcoming,Completed,Cancelled',
        ]);

        if ($event->event_name !== $request->event_name || $event->event_date !== $request->event_date) {
            $duplicateEvent = Event::where('event_name', $request->event_name)
                ->where('event_date', $request->event_date)
                ->where('event_id', '!=', $id)
                ->first();

            if ($duplicateEvent) {
                return back()->withErrors(['duplicate' => 'An event with this name and date already exists.'])->withInput();
            }
        }

        $event->update($request->all());

        $this->logActivity(
            'Updated Event',
            'Outreach Event',
            'Staff updated event: ' . $event->event_name,
            ['event_id' => $id, 'event_name' => $event->event_name, 'status' => $event->status]
        );

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $this->logActivity(
            'Deleted Event',
            'Outreach Event',
            'Staff deleted event: ' . $event->event_name,
            ['event_id' => $id, 'event_name' => $event->event_name, 'event_date' => $event->event_date]
        );

        $event->delete();

        return redirect()->route('events.index');
    }
}