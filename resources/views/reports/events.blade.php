@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
            <h3 class="mt-2 fw-bold ">
                <i class="fas fa-calendar-alt  me-2"></i>Outreach Events Summary
            </h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.events.export.pdf', request()->query()) }}" onclick="showExportAlert(event, this.href)" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-file-pdf me-1"></i> Export to PDF
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
                    <input type="date" name="date_from" class="form-control border" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Date To</label>
                    <input type="date" name="date_to" class="form-control border" value="{{ request('date_to') }}">
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
    <div class="table-responsive">
    <table class="table table-hover align-middle shadow-sm bg-white">
        <thead class="bg-light">
            <tr class="text-secondary">
                <th class="ps-3">Event Details</th>
                <th>Type</th>
                <th>Location</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr>
                    <td class="ps-3">
                        <div class="fw-bold text-primary">{{ $event->event_name }}</div>
                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : '-' }}</small>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-light text-dark border px-3">{{ $event->event_type ?? 'General' }}</span>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 200px;"><i class="fas fa-map-marker-alt text-muted me-1"></i> {{ $event->location ?? '-' }}</div>
                    </td>
                    <td>
                        @php
                            $badgeClass = match($event->status) {
                                'Upcoming' => 'bg-warning text-dark',
                                'Completed' => 'bg-success text-white',
                                'Cancelled' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} shadow-sm" style="min-width: 85px;">{{ $event->status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">No outreach events found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showExportAlert(event, url) {
    event.preventDefault(); // Stop the immediate download
    
    Swal.fire({
        title: 'Generating Report',
        text: 'Please wait while we prepare the events report...',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            setTimeout(() => {
                window.location.href = url;
            }, 500);
        }
    });
}
</script>
@endsection