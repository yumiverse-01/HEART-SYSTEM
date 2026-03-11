<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Outreach Events Report</title>
    <style>
        /* General Setup */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10.5px; 
            color: #333; 
            padding: 40px; /* Standardized page margin */
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
        .meta-table { width: 100%; border: none; margin-bottom: 15px; }
        .meta-table td { border: none; padding: 0; vertical-align: bottom; }
        .filter-text { font-size: 10px; color: #666; }
        .total-text { text-align: right; font-weight: bold; color: #0d6efd; font-size: 12px; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; }
        
        thead tr { background-color: #0d6efd; color: white; }
        thead th { 
            padding: 12px 10px; 
            text-align: left; 
            font-size: 9px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            border: 1px solid #0d6efd;
        }

        tbody tr:nth-child(even) { background-color: #f8faff; }
        
        tbody td { 
            padding: 10px; 
            border-bottom: 1px solid #dee2e6; 
            vertical-align: top; /* Changed to top for better description wrapping */
            line-height: 1.4;
        }

        /* Specific Cell Content */
        .name { font-weight: bold; color: #1a1a1a; font-size: 11px; }
        
        /* Badge Styling */
        .badge { 
            display: inline-block; 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-size: 8.5px; 
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-upcoming  { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .badge-completed { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .badge-cancelled { background: #f8d7da; color: #842029; border: 1px solid #f5c6cb; }

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
        <h2>Outreach Events Summary</h2>
        <p>Program Outreach &bull; Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="filter-text">
                <strong>Event Period:</strong> 
                @if(request('date_from') || request('date_to'))
                    {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') : 'Earliest' }}
                    —
                    {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') : 'Latest' }}
                @else
                    All Scheduled Events
                @endif
            </td>
            <td class="total-text">Total: {{ $events->count() }} Records</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="4%">#</th>
                <th width="20%">Event Details</th>
                <th width="12%">Type</th>
                <th width="12%">Date</th>
                <th width="15%">Location</th>
                <th width="12%">Status</th>
                <th width="25%">Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $i => $event)
            <tr>
                <td style="text-align: center; color: #888;">{{ $i + 1 }}</td>
                <td>
                    <div class="name">{{ $event->event_name }}</div>
                    <div style="font-size: 8.5px; color: #777; margin-top: 2px;">ID: #EVT-{{ $event->event_id }}</div>
                </td>
                <td>{{ $event->event_type ?? 'General' }}</td>
                <td style="font-weight: bold; white-space: nowrap;">
                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                </td>
                <td>{{ $event->location ?? '—' }}</td>
                <td>
                    @php
                        $badgeClass = match(ucfirst($event->status)) {
                            'Upcoming'  => 'badge-upcoming',
                            'Completed' => 'badge-completed',
                            'Cancelled' => 'badge-cancelled',
                            default     => ''
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ $event->status ?? 'N/A' }}
                    </span>
                </td>
                <td style="font-size: 9.5px; color: #555;">{{ $event->description ?? 'No description provided.' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding: 40px; color: #999; font-style: italic;">
                    No outreach events found for the specified period.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Official Outreach Report &bull; Sorsogon City &bull; Page 1 of 1
    </div>
</body>
</html>