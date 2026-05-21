@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="page-header">
    <div>
        <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted">
            <i class="fas fa-arrow-left me-1"></i>Back to Reports
        </a>
        <h3 class="mt-1"><i class="fas fa-user-check me-2"></i>Event Attendance Report</h3>
    </div>
    <div class="d-flex gap-2 flex-shrink-0">
        <a href="{{ route('reports.attendance.export.pdf', request()->query()) }}"
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
<div class="collapse mb-3 {{ request()->hasAny(['event_id', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterCollapse">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('reports.attendance') }}">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <select name="event_id" class="form-select">
                            <option value="">All Active Events</option>
                            @foreach($events as $e)
                                <option value="{{ $e->event_id }}" {{ request('event_id') == $e->event_id ? 'selected' : '' }}>
                                    {{ $e->event_name }} ({{ \Carbon\Carbon::parse($e->event_date)->format('M d') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-filter"></i></button>
                        <a href="{{ route('reports.attendance') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
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
                    <th class="ps-3">Beneficiary</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Event Info</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $a)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-primary" style="text-transform:uppercase">{{ $a->beneficiary->first_name }} {{ $a->beneficiary->last_name }}</div>
                            <small class="text-muted"><i class="far fa-envelope me-1"></i>{{ $a->beneficiary->email }}</small>
                        </td>
                        <td class="small" style="text-transform:uppercase">{{ $a->time_in  ? \Carbon\Carbon::parse($a->time_in)->format('h:i A')  : '—' }}</td>
                        <td class="small" style="text-transform:uppercase">{{ $a->time_out ? \Carbon\Carbon::parse($a->time_out)->format('h:i A') : '—' }}</td>
                        <td>
                            <span class="badge {{ $a->attendance_status == 'Present' ? 'bg-success' : 'bg-danger' }} px-3" style="text-transform:uppercase">
                                {{ $a->attendance_status }}
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="fw-bold small" style="text-transform:uppercase">{{ $a->event->event_name }}</div>
                            <small class="text-muted" style="text-transform:uppercase">{{ \Carbon\Carbon::parse($a->event->event_date)->format('M d, Y') }}</small>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No attendance records found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Cards --}}
<div class="d-md-none">
    @forelse($attendances as $a)
        <div class="table-card mb-2 p-3">
            <div class="fw-bold text-primary text-truncate" style="text-transform:uppercase">{{ $a->beneficiary->first_name }} {{ $a->beneficiary->last_name }}</div>
            <small class="text-muted d-block text-truncate"><i class="far fa-envelope me-1"></i>{{ $a->beneficiary->email }}</small>
            <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                <span class="badge {{ $a->attendance_status == 'Present' ? 'bg-success' : 'bg-danger' }} px-3" style="text-transform:uppercase">
                    {{ $a->attendance_status }}
                </span>
                @if($a->time_in)
                    <small class="text-muted" style="text-transform:uppercase">
                        <i class="far fa-clock me-1"></i>In: {{ \Carbon\Carbon::parse($a->time_in)->format('h:i A') }}
                        @if($a->time_out) · Out: {{ \Carbon\Carbon::parse($a->time_out)->format('h:i A') }} @endif
                    </small>
                @endif
            </div>
            <div class="mt-1">
                <small class="fw-bold d-block" style="text-transform:uppercase">{{ $a->event->event_name }}</small>
                <small class="text-muted" style="text-transform:uppercase">{{ \Carbon\Carbon::parse($a->event->event_date)->format('M d, Y') }}</small>
            </div>
        </div>
    @empty
        <p class="text-center text-muted py-4">No attendance records found.</p>
    @endforelse
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showExportAlert(e, url) {
        e.preventDefault();
        Swal.fire({
            title: 'Generating PDF',
            text: 'Please wait while we prepare the attendance report...',
            icon: 'info', timer: 2000, timerProgressBar: true, showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); setTimeout(() => { window.location.href = url; }, 500); }
        });
    }
</script>
@endpush

@endsection