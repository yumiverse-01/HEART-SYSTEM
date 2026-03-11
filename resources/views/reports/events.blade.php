@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted"><i class="fas fa-arrow-left"></i> Back</a>
        <h3 class="mt-2"><i class="fas fa-calendar-alt text-primary"></i> Outreach Events Summary</h3>
    </div>
    <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
        <i class="fas fa-filter"></i> Date Range
    </button>
</div>

<div class="collapse mb-4 {{ request('date_from') ? 'show' : '' }}" id="filterCollapse">
    <div class="card card-body border-0 shadow-sm bg-light">
        <form method="GET" action="{{ route('reports.events') }}" class="row g-3">
            <div class="col-md-5"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
            <div class="col-md-5"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Apply</button></div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small">
                <tr>
                    <th class="ps-4 py-3">EVENT NAME</th>
                    <th>TYPE</th>
                    <th>LOCATION</th>
                    <th>STATUS</th>
                    <th class="text-end pe-4">EVENT DATE</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr>
                    <td class="ps-4 fw-bold text-primary">{{ $event->event_name }}</td>
                    <td>{{ $event->event_type ?? 'General' }}</td>
                    <td><i class="fas fa-map-marker-alt text-muted me-1"></i> {{ $event->location }}</td>
                    <td>
                        <span class="badge {{ $event->status == 'Completed' ? 'bg-success' : ($event->status == 'Upcoming' ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ $event->status }}
                        </span>
                    </td>
                    <td class="text-end pe-4 fw-bold">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5">No events found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection