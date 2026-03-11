<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report</title>
    <style>
        /* General Setup */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 11px; 
            color: #333; 
            padding: 40px; /* Added significant page margin for printing */
            background-color: #fff;
        }

        /* Header Section */
        .header { 
            padding-bottom: 15px; 
            border-bottom: 2px solid #0d6efd; 
            margin-bottom: 25px; 
        }
        .header h2 { 
            font-size: 20px; 
            color: #0d6efd; 
            margin-bottom: 6px; 
            text-transform: uppercase;
        }
        .header p { font-size: 10px; color: #666; }

        /* Meta Info Section */
        .meta { 
            margin-bottom: 20px; 
            font-size: 11px; 
            color: #555;
            width: 100%;
        }
        .meta-table { width: 100%; border: none; }
        .meta-table td { border: none; padding: 0; }
        .total { text-align: right; font-weight: bold; color: #0d6efd; font-size: 12px; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        
        thead tr { background-color: #0d6efd; color: white; }
        thead th { 
            padding: 12px 10px; /* Increased vertical padding */
            text-align: left; 
            font-size: 9px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            border: 1px solid #0d6efd;
        }

        tbody tr:nth-child(even) { background-color: #f8faff; }
        
        tbody td { 
            padding: 10px; /* Increased cell padding */
            border-bottom: 1px solid #dee2e6; 
            vertical-align: middle; 
            line-height: 1.4;
        }

        /* Specific Cell Content */
        .name { font-weight: bold; color: #1a1a1a; font-size: 11px; }
        .sub  { font-size: 9px; color: #777; margin-top: 2px; }

        /* Badge Styling */
        .badge { 
            display: inline-block; 
            padding: 4px 10px; 
            border-radius: 4px; /* Slightly more professional than full pill */
            font-size: 9px; 
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-present { background-color: #d1e7dd; color: #0f5132; }
        .badge-absent  { background-color: #f8d7da; color: #842029; }

        /* Footer */
        .footer { 
            position: fixed; 
            bottom: 40px; 
            left: 40px; 
            right: 40px;
            text-align: right; 
            font-size: 9px; 
            color: #999; 
            border-top: 1px solid #eee; 
            padding-top: 10px; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Attendance Monitoring Report</h2>
        <p>Official Document &bull; Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>

    <table class="meta-table" style="margin-bottom: 15px;">
        <tr>
            <td style="font-size: 10px; color: #666;">
                <strong>Date Range:</strong> 
                @if(request('date_from') || request('date_to'))
                    {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') : 'Initial' }}
                    to
                    {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') : 'Present' }}
                @else
                    All Records
                @endif
            </td>
            <td class="total">Total Records: {{ $attendances->count() }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="3%">#</th>
                <th width="25%">Beneficiary</th>
                <th width="20%">Event Name</th>
                <th width="15%">Event Date</th>
                <th width="12%">Status</th>
                <th width="12%">Time In</th>
                <th width="13%">Time Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $i => $attendance)
            <tr>
                <td style="text-align: center; color: #777;">{{ $i + 1 }}</td>
                <td>
                    <div class="name">{{ $attendance->beneficiary->last_name }}, {{ $attendance->beneficiary->first_name }}</div>
                    <div class="sub">ID: #{{ $attendance->beneficiary_id }}</div>
                </td>
                <td>{{ $attendance->event->event_name }}</td>
                <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($attendance->event->event_date)->format('M d, Y') }}</td>
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
                <td colspan="7" style="text-align:center; padding: 40px; color: #999; font-style: italic;">
                    No attendance records found for the selected criteria.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        System Generated Report &bull; Page 1 of 1 &bull; Printed: {{ now()->format('Y-m-d H:i') }}
    </div>
</body>
</html>