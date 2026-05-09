<?php

namespace App\Http\Controllers;

use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Beneficiary;

class AttendanceController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $events        = Event::where('status', '!=', 'Cancelled')->orderBy('event_date', 'desc')->get();
        $beneficiaries = Beneficiary::latest()->paginate(20);
        $attendances   = Attendance::where('event_id', $request->event_id)->get();

        $this->logActivity(
            'Viewed Attendance List',
            'Attendance',
            'Staff viewed the attendance list' . ($request->event_id ? ' for event ID ' . $request->event_id : ''),
            ['event_id' => $request->event_id]
        );

        return view('attendance.index', compact('attendances', 'events', 'beneficiaries'));
    }

    public function create()
    {
        $events        = Event::where('status', '!=', 'Cancelled')->get();
        $beneficiaries = Beneficiary::all();

        $this->logActivity(
            'Viewed Attendance Create Form',
            'Attendance',
            'Staff opened the attendance creation form'
        );

        return view('attendance.create', compact('events', 'beneficiaries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beneficiary_id'    => 'required|exists:beneficiaries,beneficiary_id',
            'event_id'          => 'required|exists:events,event_id',
            'attendance_status' => 'required|in:Present,Absent',
            'time_in'           => 'nullable|date_format:H:i',
            'time_out'          => 'nullable|date_format:H:i',
        ]);

        $existingAttendance = Attendance::where('beneficiary_id', $request->beneficiary_id)
            ->where('event_id', $request->event_id)
            ->first();

        if ($existingAttendance) {
            return back()->withErrors(['duplicate' => 'This beneficiary already has an attendance record for this event.'])->withInput();
        }

        $attendanceData                = $request->all();
        $attendanceData['recorded_by'] = auth()->id() ?? 1;

        $attendance = Attendance::create($attendanceData);

        $this->logActivity(
            'Created Attendance Record',
            'Attendance',
            'Staff recorded attendance for beneficiary ID ' . $request->beneficiary_id . ' on event ID ' . $request->event_id,
            [
                'attendance_id'     => $attendance->getKey(),
                'beneficiary_id'    => $request->beneficiary_id,
                'event_id'          => $request->event_id,
                'attendance_status' => $request->attendance_status,
            ]
        );

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance recorded successfully');
    }

    public function show($id)
    {
        $attendance = Attendance::with('beneficiary', 'event')->findOrFail($id);

        $this->logActivity(
            'Viewed Attendance Record',
            'Attendance',
            'Staff viewed attendance record ID ' . $id,
            ['attendance_id' => $id]
        );

        return view('attendance.show', compact('attendance'));
    }

    public function edit($id)
    {
        $attendance    = Attendance::findOrFail($id);
        $events        = Event::where('status', '!=', 'Cancelled')->get();
        $beneficiaries = Beneficiary::all();

        $this->logActivity(
            'Viewed Attendance Edit Form',
            'Attendance',
            'Staff opened the edit form for attendance record ID ' . $id,
            ['attendance_id' => $id]
        );

        return view('attendance.edit', compact('attendance', 'events', 'beneficiaries'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'attendance_status' => 'required|in:Present,Absent',
            'time_in'           => 'nullable|date_format:H:i',
            'time_out'          => 'nullable|date_format:H:i',
        ]);

        $attendanceData                = $request->all();
        $attendanceData['recorded_by'] = auth()->id() ?? 1;

        $attendance->update($attendanceData);

        $this->logActivity(
            'Updated Attendance Record',
            'Attendance',
            'Staff updated attendance record ID ' . $id,
            [
                'attendance_id'     => $id,
                'attendance_status' => $request->attendance_status,
                'time_in'           => $request->time_in,
                'time_out'          => $request->time_out,
            ]
        );

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance updated successfully');
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'beneficiary_id'    => 'required',
            'event_id'          => 'required',
            'attendance_status' => 'required|in:Present,Absent',
            'time_in'           => 'nullable|date_format:H:i',
            'time_out'          => 'nullable|date_format:H:i',
        ]);

        $attendance = Attendance::where('beneficiary_id', $request->beneficiary_id)
            ->where('event_id', $request->event_id)
            ->first();

        $attendanceData = [
            'attendance_status' => $request->attendance_status,
            'time_in'           => $request->time_in,
            'time_out'          => $request->time_out,
            'recorded_by'       => auth()->id() ?? 1,
        ];

        if ($attendance) {
            $attendance->update($attendanceData);
            $action = 'Updated';
        } else {
            $attendanceData['beneficiary_id'] = $request->beneficiary_id;
            $attendanceData['event_id']        = $request->event_id;
            $attendance = Attendance::create($attendanceData);
            $action = 'Created';
        }

        $this->logActivity(
            $action . ' Attendance via Mark Attendance',
            'Attendance',
            'Staff marked attendance (' . $request->attendance_status . ') for beneficiary ID ' . $request->beneficiary_id . ' on event ID ' . $request->event_id,
            [
                'attendance_id'     => $attendance->getKey(),
                'beneficiary_id'    => $request->beneficiary_id,
                'event_id'          => $request->event_id,
                'attendance_status' => $request->attendance_status,
                'action'            => $action,
            ]
        );

        return redirect()->back()->with('success', 'Attendance marked successfully');
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);

        $this->logActivity(
            'Deleted Attendance Record',
            'Attendance',
            'Staff deleted attendance record ID ' . $id,
            [
                'attendance_id'  => $id,
                'beneficiary_id' => $attendance->beneficiary_id,
                'event_id'       => $attendance->event_id,
            ]
        );

        $attendance->delete();

        return redirect()->back()->with('success', 'Attendance deleted');
    }
}