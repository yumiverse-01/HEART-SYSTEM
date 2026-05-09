<?php

namespace App\Http\Controllers;

use App\Models\EventServiceRecord;
use App\Models\Event;
use App\Models\Beneficiary;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class EventServiceRecordController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = EventServiceRecord::with(['event', 'beneficiary'])->latest();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('beneficiary', function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%");
            })->orWhere('service_type', 'like', "%$search%");
        }

        $records       = $query->paginate(20);
        $events        = Event::where('status', '!=', 'Cancelled')->get();
        $beneficiaries = Beneficiary::all();

        $this->logActivity(
            'Viewed Service Records List',
            'Service Record',
            'Staff viewed the service records list',
            ['search' => $request->search]
        );

        return view('service_records.index', compact('records', 'events', 'beneficiaries'));
    }

    public function create()
    {
        $events        = Event::where('status', '!=', 'Cancelled')->get();
        $beneficiaries = Beneficiary::all();

        $this->logActivity(
            'Viewed Service Record Create Form',
            'Service Record',
            'Staff opened the service record creation form'
        );

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

        // Check for duplicate service record (same beneficiary, event, and service_date)
        $existingRecord = EventServiceRecord::where('beneficiary_id', $request->beneficiary_id)
            ->where('event_id', $request->event_id)
            ->where('service_date', $request->service_date)
            ->first();

        if ($existingRecord) {
            return back()->withErrors(['duplicate' => 'A service record for this beneficiary on this event and date already exists.'])->withInput();
        }

        $data                = $request->all();
        $data['provided_by'] = auth()->id() ?? 1;

        $record = EventServiceRecord::create($data);

        $this->logActivity(
            'Created Service Record',
            'Service Record',
            'Staff created a service record for beneficiary ID ' . $request->beneficiary_id . ' on event ID ' . $request->event_id,
            [
                'record_id'      => $record->getKey(),
                'beneficiary_id' => $request->beneficiary_id,
                'event_id'       => $request->event_id,
                'service_type'   => $request->service_type,
                'service_date'   => $request->service_date,
            ]
        );

        return redirect()->route('service-records.index')
            ->with('success', 'Service record created successfully');
    }

    public function show($id)
    {
        $record = EventServiceRecord::with('event', 'beneficiary', 'providedBy')->findOrFail($id);

        $this->logActivity(
            'Viewed Service Record Details',
            'Service Record',
            'Staff viewed service record ID ' . $id,
            ['record_id' => $id]
        );

        return view('service_records.show', compact('record'));
    }

    public function edit($id)
    {
        $record        = EventServiceRecord::findOrFail($id);
        $events        = Event::where('status', '!=', 'Cancelled')->get();
        $beneficiaries = Beneficiary::all();

        $this->logActivity(
            'Viewed Service Record Edit Form',
            'Service Record',
            'Staff opened the edit form for service record ID ' . $id,
            ['record_id' => $id]
        );

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

        if ($record->beneficiary_id != $request->beneficiary_id || 
            $record->event_id != $request->event_id || 
            $record->service_date !== $request->service_date) {
            $duplicateRecord = EventServiceRecord::where('beneficiary_id', $request->beneficiary_id)
                ->where('event_id', $request->event_id)
                ->where('service_date', $request->service_date)
                ->where('service_id', '!=', $id)
                ->first();

            if ($duplicateRecord) {
                return back()->withErrors(['duplicate' => 'A service record for this beneficiary on this event and date already exists.'])->withInput();
            }
        }

        $data                = $request->all();
        $data['provided_by'] = auth()->id() ?? 1;

        $record->update($data);

        $this->logActivity(
            'Updated Service Record',
            'Service Record',
            'Staff updated service record ID ' . $id,
            [
                'record_id'      => $id,
                'beneficiary_id' => $request->beneficiary_id,
                'event_id'       => $request->event_id,
                'service_type'   => $request->service_type,
                'service_date'   => $request->service_date,
            ]
        );

        return redirect()->route('service-records.index')
            ->with('success', 'Service record updated successfully');
    }

    public function destroy($id)
    {
        $record = EventServiceRecord::findOrFail($id);

        $this->logActivity(
            'Deleted Service Record',
            'Service Record',
            'Staff deleted service record ID ' . $id,
            [
                'record_id'      => $id,
                'beneficiary_id' => $record->beneficiary_id,
                'event_id'       => $record->event_id,
                'service_type'   => $record->service_type,
            ]
        );

        $record->delete();

        return redirect()->route('service-records.index')
            ->with('success', 'Service record deleted');
    }
}