@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3><i class="fas fa-chart-line"></i> Report & Analytics</h3>
    <p class="text-muted">Generate and view reports for evaluation and decision-making</p>
</div>

<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(to right, #0d6efd, #004dc7); color: white;">
    <div class="card-body p-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Generate New Report</h4>
            <p class="mb-0 opacity-75">Create comprehensive reports for tracking and analysis</p>
        </div>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle fw-bold text-primary" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-file-export me-1"></i> Generate Report
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li><a class="dropdown-item" href="{{ route('reports.beneficiaries') }}"><i class="fas fa-users me-2"></i> Beneficiary List</a></li>
                <li><a class="dropdown-item" href="{{ route('reports.events') }}"><i class="fas fa-calendar-check me-2"></i> Outreach Events</a></li>
                <li><a class="dropdown-item" href="{{ route('reports.attendance') }}"><i class="fas fa-clipboard-list me-2"></i> Attendance Report</a></li>
                <li><a class="dropdown-item" href="{{ route('reports.service-records') }}"><i class="fas fa-hand-holding-medical me-2"></i> Service Records</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-4">
    @php
        $reportTypes = [
            ['title' => 'Beneficiary Demographics', 'desc' => 'Registration trends and profile analytics', 'route' => 'reports.beneficiaries', 'icon' => 'fa-users'],
            ['title' => 'Event Outreach Summary', 'desc' => 'Overview of past and upcoming outreach activities', 'route' => 'reports.events', 'icon' => 'fa-calendar-alt'],
            ['title' => 'Attendance Monitoring', 'desc' => 'Detailed records of beneficiary participation', 'route' => 'reports.attendance', 'icon' => 'fa-user-check'],
            ['title' => 'Health Service Records', 'desc' => 'Logs of medical services and medicine distributed', 'route' => 'reports.service-records', 'icon' => 'fa-notes-medical'],
        ];
    @endphp

    @foreach($reportTypes as $report)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas {{ $report['icon'] }} text-primary fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $report['title'] }}</h5>
                        <p class="small text-muted mb-0">Updated as of {{ date('M d, Y') }}</p>
                    </div>
                </div>
                <p class="text-secondary small mb-4">{{ $report['desc'] }}</p>
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route($report['route']) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-eye me-1"></i> View Report
                        </a>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-secondary btn-sm w-100" onclick="Swal.fire('Exporting...', 'Preparing CSV download', 'success')">
                            <i class="fas fa-download me-1"></i> Export CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection