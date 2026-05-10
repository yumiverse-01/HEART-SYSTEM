@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="page-header">
    <div>
        <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted">
            <i class="fas fa-arrow-left me-1"></i>Back to Reports
        </a>
        <h3 class="mt-1"><i class="fas fa-calendar-alt me-2"></i>Outreach Events Summary</h3>
    </div>
    <div class="d-flex gap-2 flex-shrink-0">
        <a href="{{ route('reports.events.export.pdf', request()->query()) }}"
           onclick="showExportAlert(event, this.href)"
           class="btn btn-outline-secondary">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
            <i class="fas fa-filter me-1"></i> Filters
        </button>
    </div>
</div>

{{-- Filters --}}
<div class="collapse mb-3 {{ request()->hasAny(['date_from', 'date_to']) ? 'show' : '' }}" id="filterCollapse">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('reports.events') }}">
                <div class="row g-2">
                    <div class="col-6 col-md-4">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
                    </div>
                    <div class="col-6 col-md-4">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
                    </div>
                    <div class="col-12 col-md-4 d-flex gap-1">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-filter"></i></button>
                        <a href="{{ route('reports.events') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Desktop Table --}}
<div class="table-card d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-secondary small text-uppercase">
                    <th class="ps-3">Event Details</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th class="text-end pe-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    @php
                        $statusClass = match($event->status) {
                            'Upcoming'  => 'bg-warning text-dark',
                            'Completed' => 'bg-success text-white',
                            'Cancelled' => 'bg-danger text-white',
                            default     => 'bg-secondary text-white'
                        };
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-primary">{{ $event->event_name }}</div>
                            <small class="text-muted">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : '—' }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 rounded-pill">{{ $event->event_type ?? 'General' }}</span>
                        </td>
                        <td>
                            <div class="text-truncate small" style="max-width:200px;">
                                <i class="fas fa-map-marker-alt text-muted me-1"></i>{{ $event->location ?? '—' }}
                            </div>
                        </td>
                        <td class="text-end pe-3">
                            <span class="badge {{ $statusClass }} px-3">{{ $event->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">No outreach events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Cards --}}
<div class="d-md-none">
    @forelse($events as $event)
        @php
            $statusClass = match($event->status) {
                'Upcoming'  => 'bg-warning text-dark',
                'Completed' => 'bg-success text-white',
                'Cancelled' => 'bg-danger text-white',
                default     => 'bg-secondary text-white'
            };
        @endphp
        <div class="table-card mb-2 p-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div style="min-width:0;">
                    <div class="fw-bold text-primary text-truncate">{{ $event->event_name }}</div>
                    <small class="text-muted d-block">
                        <i class="far fa-calendar-alt me-1"></i>
                        {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : '—' }}
                    </small>
                    <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                        <span class="badge bg-light text-dark border rounded-pill">{{ $event->event_type ?? 'General' }}</span>
                        <small class="text-muted text-truncate">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location ?? '—' }}
                        </small>
                    </div>
                </div>
                <span class="badge {{ $statusClass }} px-3 flex-shrink-0">{{ $event->status }}</span>
            </div>
        </div>
    @empty
        <p class="text-center text-muted py-4">No outreach events found.</p>
    @endforelse
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showExportAlert(e, url) {
        e.preventDefault();
        Swal.fire({
            title: 'Generating Report',
            text: 'Please wait while we prepare the events report...',
            icon: 'info', timer: 2000, timerProgressBar: true, showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); setTimeout(() => { window.location.href = url; }, 500); }
        });
    }
</script>
@endpush

@endsection