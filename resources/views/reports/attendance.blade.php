@extends('layouts.app')

@section('content')
{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
        <h3 class="mt-3 fw-bold"><i class="fas fa-user-check text-navy me-2"></i>Event Attendance Report</h3>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.attendance.export.pdf', request()->query()) }}" onclick="showExportAlert(event, this.href)" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-file-pdf me-1"></i> Export to PDF
        </a>
        <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
            <i class="fas fa-filter me-1"></i> Filters
        </button>
    </div>
</div>

{{-- Filters --}}
<div class="collapse mb-4 {{ request()->hasAny(['event_id', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterCollapse">
    <div class="card card-body border bg-white p-4" style="box-shadow: none;">
        <form method="GET" action="{{ route('reports.attendance') }}" class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted text-uppercase">Specific Event</label>
                <select name="event_id" class="form-select border">
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
                <input type="date" name="date_from" class="form-control border" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Date To</label>
                <input type="date" name="date_to" class="form-control border" value="{{ request('date_to') }}">
            </div>
            <div class="col-12 text-end mt-3">
                <a href="{{ route('reports.attendance') }}" class="btn btn-link btn-sm text-secondary text-decoration-none me-3">Reset Filters</a>
                <button type="submit" class="btn btn-primary px-5">Apply Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    {{-- TABLE LAYOUT COPIED FROM YOUR ATTENDANCE TABLE --}}
    <table class="table table-hover align-middle border bg-white">
        <thead class="bg-light">
            <tr class="text-secondary">
                <th class="ps-3">Beneficiary Name</th>
                <!-- <th>Category</th> -->
                <th>Time In</th>
                <th>Time Out</th>
                <th>Status</th>
                <th class="text-end pe-3">Event Info</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $a)
                @php
                    $b = $a->beneficiary;
                @endphp
                <tr>
                    <td class="ps-3">
                        <div class="fw-bold text-primary">{{ $b->first_name }} {{ $b->last_name }}</div>
                        <small class="text-muted"><i class="far fa-envelope me-1"></i> {{ $b->email }}</small>
                    </td>
                    <!-- <td><span class="badge bg-light text-dark border px-3">{{ $b->category ?? 'General' }}</span></td> -->
                    <td>{{ $a->time_in ? \Carbon\Carbon::parse($a->time_in)->format('h:i A') : '—' }}</td>
                    <td>{{ $a->time_out ? \Carbon\Carbon::parse($a->time_out)->format('h:i A') : '—' }}</td>
                    <td>
                        <span class="badge {{ $a->attendance_status == 'Present' ? 'bg-success' : 'bg-danger' }}">
                            {{ $a->attendance_status }}
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <small class="fw-bold d-block">{{ $a->event->event_name }}</small>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($a->event->event_date)->format('M d, Y') }}</small>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">No attendance records found for the selected criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showExportAlert(e, url) {
        e.preventDefault(); 
        Swal.fire({
            title: 'Generating PDF',
            text: 'Please wait while we prepare the attendance report...',
            icon: 'info',
            timer: 2000,
            timerProgressBar: true,
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