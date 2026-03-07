<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Event;
use App\Models\Attendance;

class DashboardController extends Controller
{
    public function index()
    {
        $beneficiaryCount = Beneficiary::count();
        $eventCount = Event::where('status', '!=', 'Cancelled')->count();
        $attendanceCount = Attendance::count();
        $recentEvents = Event::where('status', 'Upcoming')
            ->orderBy('event_date', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'beneficiaryCount',
            'eventCount',
            'attendanceCount',
            'recentEvents'
        ));
    }
}