<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Beneficiary;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::where('status', '!=', 'Cancelled')->get();
        $attendances = Attendance::with('beneficiary', 'event')->get();
        // always load beneficiaries (we only display them when an event is chosen)
        $beneficiaries = Beneficiary::all();

        return view('attendance.index', compact('attendances', 'events', 'beneficiaries'));
    }

    public function create()
    {
        $events = Event::where('status', '!=', 'Cancelled')->get();
        $beneficiaries = Beneficiary::all();

        return view('attendance.create', compact('events', 'beneficiaries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beneficiary_id' => 'required|exists:beneficiaries,beneficiary_id',
            'event_id' => 'required|exists:events,event_id',
            'attendance_status' => 'required|in:Present,Absent',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i'
        ]);

        $attendanceData = $request->all();
        $attendanceData['recorded_by'] = auth()->id() ?? 1;

        Attendance::create($attendanceData);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance recorded successfully');
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $events = Event::where('status', '!=', 'Cancelled')->get();
        $beneficiaries = Beneficiary::all();

        return view('attendance.edit', compact('attendance', 'events', 'beneficiaries'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'attendance_status' => 'required|in:Present,Absent',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i'
        ]);

        $attendanceData = $request->all();
        $attendanceData['recorded_by'] = auth()->id() ?? 1;

        $attendance->update($attendanceData);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance updated successfully');
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'beneficiary_id' => 'required',
            'event_id' => 'required',
            'attendance_status' => 'required|in:Present,Absent',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i'
        ]);

        $attendance = Attendance::where('beneficiary_id', $request->beneficiary_id)
            ->where('event_id', $request->event_id)
            ->first();

        $attendanceData = [
            'attendance_status' => $request->attendance_status,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'recorded_by' => auth()->id() ?? 1
        ];

        if ($attendance) {
            $attendance->update($attendanceData);
        } else {
            $attendanceData['beneficiary_id'] = $request->beneficiary_id;
            $attendanceData['event_id'] = $request->event_id;
            Attendance::create($attendanceData);
        }

        return redirect()->back()->with('success', 'Attendance marked successfully');
    }

    public function show($id)
    {

        $attendance = Attendance::with('beneficiary','event')
            ->findOrFail($id);

        return view('attendance.show', compact('attendance'));

    }

    public function destroy($id)
    {

        $attendance = Attendance::findOrFail($id);

        $attendance->delete();

        return redirect()->back()->with('success','Attendance deleted');

    }

}