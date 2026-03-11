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
                <i class="fas fa-user-check text-primary me-2"></i>Event Attendance Report
            </h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.attendance.export.pdf', request()->query()) }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-file-pdf me-1"></i> Print
            </a>
            <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="fas fa-filter me-1"></i> Filters
            </button>
        </div>
    </div>

    {{-- Filter Section - Buttons moved to col-12 below inputs --}}
    <div class="collapse mb-4 {{ request()->hasAny(['event_id', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterCollapse">
        <div class="card card-body border-0 shadow-sm bg-light p-4">
            <form method="GET" action="{{ route('reports.attendance') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Specific Event</label>
                    <select name="event_id" class="form-select border-0 shadow-sm">
                        <option value="">All Active Events</option>
                        @foreach($events as $e)
                            <option value="{{ $e->event_id }}" {{ request('event_id') == $e->event_id ? 'selected' : '' }}>
                                {{ $e->event_name }} ({{ \Carbon\Carbon::parse($e->event_date)->format('M d') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Date From</label>
                    <input type="date" name="date_from" class="form-control border-0 shadow-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Date To</label>
                    <input type="date" name="date_to" class="form-control border-0 shadow-sm" value="{{ request('date_to') }}">
                </div>
                
                {{-- Reset and Submit row exactly like Service Records --}}
                <div class="col-12 text-end mt-3">
                    <a href="{{ route('reports.attendance') }}" class="btn btn-link btn-sm text-secondary text-decoration-none me-3">Reset Filters</a>
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
                        <th class="ps-4 py-3 small fw-bold text-uppercase">Beneficiary</th>
                        <th class="small fw-bold text-uppercase">Event Attended</th>
                        <th class="small fw-bold text-uppercase">Date</th>
                        <th class="text-end pe-4 small fw-bold text-uppercase">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $a)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 35px; height: 35px; font-size: 0.8rem; font-weight: bold;">
                                    {{ substr($a->beneficiary->first_name, 0, 1) }}{{ substr($a->beneficiary->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $a->beneficiary->first_name }} {{ $a->beneficiary->last_name }}</div>
                                    <small class="text-muted text-uppercase" style="font-size: 0.7rem;">ID: #{{ $a->beneficiary_id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                {{ $a->event->event_name }}
                            </span>
                        </td>
                        <td>
                            <div class="text-dark fw-medium">{{ \Carbon\Carbon::parse($a->event->event_date)->format('M d, Y') }}</div>
                        </td>
                        <td class="text-end pe-4">
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                <i class="fas fa-check-circle me-1"></i> Present
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x mb-3 text-light"></i>
                            <p class="text-muted mb-0">No attendance data found for the selected criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection