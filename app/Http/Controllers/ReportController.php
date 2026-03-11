<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Beneficiary;
use App\Models\Event;
use App\Models\EventServiceRecord;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function beneficiaries(Request $request)
    {
        $query = Beneficiary::query();

        if ($request->filled('date_from')) {
            $query->whereDate('date_registered', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_registered', '<=', $request->date_to);
        }

        $beneficiaries = $query->latest('date_registered')->get();

        return view('reports.beneficiaries', compact('beneficiaries'));
    }

    public function events(Request $request)
    {
        $query = Event::query();

        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $events = $query->latest('event_date')->get();

        return view('reports.events', compact('events'));
    }

    public function attendance(Request $request)
    {
        $query = Attendance::with('beneficiary', 'event');

        if ($request->filled('date_from')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->whereDate('event_date', '>=', $request->date_from);
            });
        }
        if ($request->filled('date_to')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->whereDate('event_date', '<=', $request->date_to);
            });
        }
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $attendances = $query->get();
        $events = Event::where('status', '!=', 'Cancelled')->get();

        return view('reports.attendance', compact('attendances', 'events'));
    }

    public function serviceRecords(Request $request)
    {
        $query = EventServiceRecord::with('beneficiary', 'event', 'providedBy');

        if ($request->filled('date_from')) {
            $query->whereDate('service_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('service_date', '<=', $request->date_to);
        }
        if ($request->filled('service_type')) {
            $query->where('service_type', 'like', '%' . $request->service_type . '%');
        }
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $records = $query->latest('service_date')->get();
        $events = Event::where('status', '!=', 'Cancelled')->get();

        return view('reports.service_records', compact('records', 'events'));
    }
}