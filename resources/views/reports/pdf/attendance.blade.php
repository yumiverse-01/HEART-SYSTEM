<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        .header { padding: 20px 0 15px; border-bottom: 2px solid #0d6efd; margin-bottom: 20px; }
        .header h2 { font-size: 18px; color: #0d6efd; margin-bottom: 4px; }
        .header p { font-size: 10px; color: #888; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 10px; color: #666; }
        .total { font-size: 11px; font-weight: bold; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #0d6efd; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        tbody tr:nth-child(even) { background-color: #f5f8ff; }
        tbody tr:nth-child(odd)  { background-color: #ffffff; }
        tbody td { padding: 8px 10px; border-bottom: 1px solid #e9ecef; vertical-align: middle; }
        .name { font-weight: bold; color: #1a1a1a; }
        .sub  { font-size: 9px; color: #888; margin-top: 2px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; }
        .badge-present { background: #d1e7dd; color: #0f5132; }
        .badge-absent  { background: #f8d7da; color: #842029; }
        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #aaa; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Attendance Monitoring Report</h2>
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>

    <div class="meta">
        <span>
            @if(request('date_from') || request('date_to'))
                Filter:
                {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') : 'Start' }}
                —
                {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') : 'Present' }}
            @else
                Showing all records
            @endif
        </span>
        <span class="total">Total: {{ $attendances->count() }} records</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Beneficiary</th>
                <th>Event</th>
                <th>Event Date</th>
                <th>Status</th>
                <th>Time In</th>
                <th>Time Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $i => $attendance)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <div class="name">{{ $attendance->beneficiary->last_name }}, {{ $attendance->beneficiary->first_name }}</div>
                    <div class="sub">ID: #{{ $attendance->beneficiary_id }}</div>
                </td>
                <td>{{ $attendance->event->event_name }}</td>
                <td>{{ \Carbon\Carbon::parse($attendance->event->event_date)->format('M d, Y') }}</td>
                <td>
                    <span class="badge {{ $attendance->attendance_status === 'Present' ? 'badge-present' : 'badge-absent' }}">
                        {{ $attendance->attendance_status }}
                    </span>
                </td>
                <td>{{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : '—' }}</td>
                <td>{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding: 20px; color: #aaa;">No records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        This report was automatically generated &bull; {{ now()->format('M d, Y h:i A') }}
    </div>
</body>
</html>