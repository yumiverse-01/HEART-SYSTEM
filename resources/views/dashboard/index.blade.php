@extends('layouts.app')

@section('content')

<h3 class="mb-4"><i class="fas fa-chart-line"></i> Dashboard</h3>

<div class="row">

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card-stat" style="background:linear-gradient(135deg,#10b981,#059669)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Total Beneficiaries</h6>
                    <h2 class="mb-0">{{ $beneficiaryCount }}</h2>
                </div>
                <i class="fas fa-users" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card-stat" style="background:linear-gradient(135deg,#a855f7,#7e22ce)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Upcoming Events</h6>
                    <h2 class="mb-0">{{ $eventCount }}</h2>
                </div>
                <i class="fas fa-calendar-alt" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card-stat" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Total Attendance</h6>
                    <h2 class="mb-0">{{ $attendanceCount }}</h2>
                </div>
                <i class="fas fa-clipboard-check" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card-stat" style="background:linear-gradient(135deg,#f97316,#ea580c)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Services Provided</h6>
                    <h2 class="mb-0">--</h2>
                </div>
                <i class="fas fa-hospital-user" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

</div>

<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-list-check"></i> Recent Outreach Events</h5>
    </div>
    <div class="card-body">
        @forelse($recentEvents as $event)
            <div class="border-bottom py-3 d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $event->event_name }}</strong>
                    <br>
                    <small class="text-muted">
                        <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                    </small>
                </div>
                <span class="badge bg-info">{{ $event->event_type ?? 'General' }}</span>
            </div>
        @empty
            <p class="text-muted mb-0">No recent events</p>
        @endforelse
    </div>
</div>

@endsection