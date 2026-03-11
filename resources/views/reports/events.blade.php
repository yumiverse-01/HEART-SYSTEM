@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h3 class="mt-2 fw-bold text-dark">
                <i class="fas fa-calendar-alt text-primary me-2"></i>Outreach Events Summary
            </h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.events.export.pdf', request()->query()) }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-file-pdf me-1"></i> Print
            </a>
            <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="fas fa-filter me-1"></i> Filters
            </button>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="collapse mb-4 {{ request()->hasAny(['date_from', 'date_to']) ? 'show' : '' }}" id="filterCollapse">
        <div class="card card-body border-0 shadow-sm bg-light p-4">
            <form method="GET" action="{{ route('reports.events') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Date From</label>
                    <input type="date" name="date_from" class="form-control border-0 shadow-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Date To</label>
                    <input type="date" name="date_to" class="form-control border-0 shadow-sm" value="{{ request('date_to') }}">
                </div>
                
                {{-- Reset and Submit Row --}}
                <div class="col-12 text-end mt-3">
                    <a href="{{ route('reports.events') }}" class="btn btn-link btn-sm text-secondary text-decoration-none me-3">Reset Filters</a>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th class="ps-4 py-3 small fw-bold text-uppercase">Event Details</th>
                        <th class="small fw-bold text-uppercase">Type</th>
                        <th class="small fw-bold text-uppercase">Location</th>
                        <th class="small fw-bold text-uppercase">Status</th>
                        <th class="text-end pe-4 small fw-bold text-uppercase">Event Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-primary">{{ $event->event_name }}</div>
                            <small class="text-muted">ID: #EVT-{{ $event->event_id }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                {{ $event->event_type ?? 'General' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-dark">
                                <i class="fas fa-map-marker-alt text-danger me-2 shadow-sm"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = $event->status == 'Completed' ? 'success' : ($event->status == 'Upcoming' ? 'warning' : 'danger');
                            @endphp
                            <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-3">
                                <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> {{ $event->status }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($event->event_date)->diffForHumans() }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x mb-3 text-light"></i>
                            <p class="text-muted mb-0">No outreach events found for the selected period.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection