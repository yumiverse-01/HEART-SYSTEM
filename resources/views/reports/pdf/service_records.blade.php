<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Health Service Records Report</title>
    <style>
        /* General Setup */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10px; /* Slightly smaller to fit 8 columns comfortably */
            color: #333; 
            padding: 40px; 
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
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        
        thead tr { background-color: #0d6efd; color: white; }
        thead th { 
            padding: 12px 8px; 
            text-align: left; 
            font-size: 9px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            border: 1px solid #0d6efd;
        }

        tbody tr:nth-child(even) { background-color: #f8faff; }
        
        tbody td { 
            padding: 10px 8px; 
            border-bottom: 1px solid #dee2e6; 
            vertical-align: top; /* Align to top for long text fields */
            line-height: 1.4;
            word-wrap: break-word;
        }

        /* Specific Cell Content */
        .name { font-weight: bold; color: #1a1a1a; font-size: 10.5px; }
        .sub  { font-size: 8.5px; color: #777; margin-top: 2px; }

        /* Badge Styling */
        .badge { 
            display: inline-block; 
            padding: 3px 7px; 
            border-radius: 4px;
            font-size: 8.5px; 
            font-weight: bold;
            background-color: #e7f0ff; 
            color: #0d6efd;
            border: 1px solid #d0e3ff;
            text-transform: uppercase;
        }

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
        <h2>Health Service Records Report</h2>
        <p>Medical Outreach Documentation &bull; Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="filter-text">
                <strong>Service Period:</strong> 
                @if(request('date_from') || request('date_to'))
                    {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') : 'Start' }}
                    —
                    {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') : 'Present' }}
                @else
                    All Historical Service Logs
                @endif
            </td>
            <td class="total-text">Total: {{ $records->count() }} Records</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="4%">#</th>
                <th width="18%">Beneficiary</th>
                <th width="14%">Event</th>
                <th width="12%">Service</th>
                <th width="15%">Diagnosis</th>
                <th width="15%">Treatment</th>
                <th width="12%">Provider</th>
                <th width="10%">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $record)
            <tr>
                <td style="text-align: center; color: #888;">{{ $i + 1 }}</td>
                <td>
                    <div class="name">{{ $record->beneficiary->last_name }}, {{ $record->beneficiary->first_name }}</div>
                    <div class="sub">ID: #{{ $record->beneficiary_id }}</div>
                </td>
                <td>{{ $record->event->event_name }}</td>
                <td><span class="badge">{{ $record->service_type ?? 'N/A' }}</span></td>
                <td style="font-size: 9px;">{{ $record->diagnosis ?? '—' }}</td>
                <td style="font-size: 9px;">{{ $record->treatment_given ?? '—' }}</td>
                <td>
                    @if($record->providedBy)
                        <div style="font-weight: bold;">{{ $record->providedBy->first_name }} {{ $record->providedBy->last_name }}</div>
                    @else
                        <span style="color:#aaa; font-style: italic;">Unknown</span>
                    @endif
                </td>
                <td>
                    <div style="font-weight: bold;">{{ \Carbon\Carbon::parse($record->service_date)->format('M d, Y') }}</div>
                    <div class="sub">{{ \Carbon\Carbon::parse($record->service_date)->format('h:i A') }}</div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding: 40px; color: #999; font-style: italic;">
                    No health service records found for the selected criteria.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Confidential Medical Report &bull; {{ now()->format('M d, Y h:i A') }} &bull; Page 1 of 1
    </div>
</body>
</html>