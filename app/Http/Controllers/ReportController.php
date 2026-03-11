<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Exports\BeneficiariesExport;
use App\Exports\EventsExport;
use App\Exports\ServiceRecordsExport;
use App\Models\Attendance;
use App\Models\Beneficiary;
use App\Models\Event;
use App\Models\EventServiceRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function exportBeneficiaries(Request $request)
    {
        return Excel::download(
            new BeneficiariesExport($request->date_from, $request->date_to),
            'beneficiaries-report.csv'
        );
    }

    public function exportBeneficiariesPdf(Request $request)
    {
        $query = Beneficiary::query()
            ->when($request->date_from, fn($q) => $q->whereDate('date_registered', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('date_registered', '<=', $request->date_to));

        $beneficiaries = $query->latest('date_registered')->get();

        $pdf = Pdf::loadView('reports.pdf.beneficiaries', compact('beneficiaries'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('beneficiaries-report.pdf');
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

    public function exportEvents(Request $request)
    {
        return Excel::download(
            new EventsExport($request->date_from, $request->date_to),
            'events-report.csv'
        );
    }
    
    public function exportEventsPdf(Request $request)
    {
        $events = Event::query()
            ->when($request->date_from, fn($q) => $q->whereDate('event_date', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('event_date', '<=', $request->date_to))
            ->latest('event_date')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.events', compact('events'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('events-report.pdf');
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

    public function exportAttendance(Request $request)
    {
        return Excel::download(
            new AttendanceExport($request->date_from, $request->date_to, $request->event_id),
            'attendance-report.csv'
        );
    }

    public function exportAttendancePdf(Request $request)
    {
        $attendances = Attendance::with('beneficiary', 'event')
            ->when($request->event_id,  fn($q) => $q->where('event_id', $request->event_id))
            ->when($request->date_from, fn($q) => $q->whereHas('event', fn($q) => $q->whereDate('event_date', '>=', $request->date_from)))
            ->when($request->date_to,   fn($q) => $q->whereHas('event', fn($q) => $q->whereDate('event_date', '<=', $request->date_to)))
            ->get();

        $pdf = Pdf::loadView('reports.pdf.attendance', compact('attendances'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('attendance-report.pdf');
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

    public function exportServiceRecords(Request $request)
    {
        return Excel::download(
            new ServiceRecordsExport($request->date_from, $request->date_to, $request->event_id, $request->service_type),
            'service-records-report.csv'
        );
    }

    public function exportServiceRecordsPdf(Request $request)
    {
        $records = EventServiceRecord::with('beneficiary', 'event', 'providedBy')
            ->when($request->date_from,    fn($q) => $q->whereDate('service_date', '>=', $request->date_from))
            ->when($request->date_to,      fn($q) => $q->whereDate('service_date', '<=', $request->date_to))
            ->when($request->event_id,     fn($q) => $q->where('event_id', $request->event_id))
            ->when($request->service_type, fn($q) => $q->where('service_type', 'like', '%' . $request->service_type . '%'))
            ->latest('service_date')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.service_records', compact('records'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('service-records-report.pdf');
    }
}