<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Beneficiary Registration Report</title>
    <style>
        /* General Setup */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10px; /* Dropped slightly to 10px to accommodate more columns */
            color: #333; 
            padding: 40px; /* Page margin for printing */
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

        /* Meta Info Section (Table-based for alignment) */
        .meta-table { width: 100%; border: none; margin-bottom: 15px; }
        .meta-table td { border: none; padding: 0; vertical-align: bottom; }
        .filter-text { font-size: 10px; color: #666; }
        .total-text { text-align: right; font-weight: bold; color: #0d6efd; font-size: 12px; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; }
        
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
            vertical-align: middle; 
            line-height: 1.3;
        }

        /* Specific Cell Content */
        .name { font-weight: bold; color: #1a1a1a; font-size: 10.5px; }
        .sub  { font-size: 8.5px; color: #777; margin-top: 2px; }

        /* Badge Styling for Sex */
        .badge { 
            display: inline-block; 
            padding: 3px 7px; 
            border-radius: 4px;
            font-size: 8.5px; 
            font-weight: bold;
            background-color: #e7f0ff; 
            color: #0d6efd;
            border: 1px solid #d0e3ff;
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
        <h2>Beneficiary Registration Report</h2>
        <p>Official Registration Records &bull; Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="filter-text">
                <strong>Registration Period:</strong> 
                @if(request('date_from') || request('date_to'))
                    {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') : 'Start' }}
                    —
                    {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') : 'Present' }}
                @else
                    All Historical Records
                @endif
            </td>
            <td class="total-text">Total: {{ $beneficiaries->count() }} Beneficiaries</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="4%">#</th>
                <th width="20%">Full Name</th>
                <th width="18%">Email</th>
                <th width="8%">Sex</th>
                <th width="6%">Age</th>
                <th width="14%">Contact</th>
                <th width="15%">Guardian</th>
                <th width="15%">Registration Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($beneficiaries as $i => $b)
            <tr>
                <td style="text-align: center; color: #888;">{{ $i + 1 }}</td>
                <td>
                    <div class="name">{{ $b->last_name }}, {{ $b->first_name }}</div>
                    <div class="sub">ID: #{{ $b->beneficiary_id }}</div>
                </td>
                <td style="word-break: break-all;">{{ $b->email ?? '—' }}</td>
                <td style="text-align: center;">
                    <span class="badge">{{ $b->sex ?? '—' }}</span>
                </td>
                <td style="text-align: center;">{{ $b->age ?? '—' }}</td>
                <td>{{ $b->contact_number ?? '—' }}</td>
                <td>{{ $b->guardian_name ?? '—' }}</td>
                <td>
                    <div style="font-weight: bold;">{{ \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') }}</div>
                    <div class="sub">{{ \Carbon\Carbon::parse($b->date_registered)->format('h:i A') }}</div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding: 40px; color: #999; font-style: italic;">
                    No beneficiary registration records found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        System Generated Document &bull; {{ now()->format('M d, Y h:i A') }} &bull; Sorsogon City
    </div>

</body>
</html>