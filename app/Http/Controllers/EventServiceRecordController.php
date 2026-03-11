<?php

namespace App\Http\Controllers;

use App\Models\EventServiceRecord;
use App\Models\Event;
use App\Models\Beneficiary;
use Illuminate\Http\Request;

class EventServiceRecordController extends Controller
{
    public function index(Request $request)
    {
        $records = EventServiceRecord::with('event', 'beneficiary', 'providedBy')->latest()->get();

        $events = Event::where('status', '!=', 'Cancelled')->get();
        $beneficiaries = Beneficiary::all();

        return view('service_records.index', compact('records', 'events', 'beneficiaries'));
    }

    public function create()
    {
        $events = Event::where('status', '!=', 'Cancelled')->get();
        $beneficiaries = Beneficiary::all();

        return view('service_records.create', compact('events', 'beneficiaries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id'        => 'required|exists:events,event_id',
            'beneficiary_id'  => 'required|exists:beneficiaries,beneficiary_id',
            'service_type'    => 'nullable|string|max:255',
            'diagnosis'       => 'nullable|string',
            'treatment_given' => 'nullable|string',
            'remarks'         => 'nullable|string',
            'service_date'    => 'required|date',
        ]);

        $data = $request->all();
        $data['provided_by'] = auth()->id() ?? 1;

        EventServiceRecord::create($data);

        return redirect()->route('service-records.index')
            ->with('success', 'Service record created successfully');
    }

    public function show($id)
    {
        $record = EventServiceRecord::with('event', 'beneficiary', 'providedBy')
            ->findOrFail($id);

        return view('service_records.show', compact('record'));
    }

    public function edit($id)
    {
        $record = EventServiceRecord::findOrFail($id);
        $events = Event::where('status', '!=', 'Cancelled')->get();
        $beneficiaries = Beneficiary::all();

        return view('service_records.edit', compact('record', 'events', 'beneficiaries'));
    }

    public function update(Request $request, $id)
    {
        $record = EventServiceRecord::findOrFail($id);

        $request->validate([
            'event_id'        => 'required|exists:events,event_id',
            'beneficiary_id'  => 'required|exists:beneficiaries,beneficiary_id',
            'service_type'    => 'nullable|string|max:255',
            'diagnosis'       => 'nullable|string',
            'treatment_given' => 'nullable|string',
            'remarks'         => 'nullable|string',
            'service_date'    => 'required|date',
        ]);

        $data = $request->all();
        $data['provided_by'] = auth()->id() ?? 1;

        $record->update($data);

        return redirect()->route('service-records.index')
            ->with('success', 'Service record updated successfully');
    }

    public function destroy($id)
    {
        $record = EventServiceRecord::findOrFail($id);

        $record->delete();

        return redirect()->route('service-records.index')
            ->with('success', 'Service record deleted');
    }
}