@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
            <h3 class="mt-2 fw-bold">
                <i class="fas fa-hand-holding-medical me-2"></i>Service Records Report
            </h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.service-records.export.pdf', request()->query()) }}" onclick="showExportAlert(event, this.href)" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-file-pdf me-1"></i> Export to PDF
            </a>
            <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="fas fa-filter me-1"></i> Filters
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="collapse mb-4 {{ request()->hasAny(['date_from', 'event_id', 'service_type']) ? 'show' : '' }}" id="filterCollapse">
        <div class="card card-body border bg-white p-4" style="box-shadow: none;">
            <form method="GET" action="{{ route('reports.service-records') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Date From</label>
                    <input type="date" name="date_from" class="form-control border" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Date To</label>
                    <input type="date" name="date_to" class="form-control border" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Outreach Event</label>
                    <select name="event_id" class="form-select border">
                        <option value="">All Events</option>
                        @foreach($events as $event)
                            <option value="{{ $event->event_id }}" {{ request('event_id') == $event->event_id ? 'selected' : '' }}>
                                {{ $event->event_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Service Type</label>
                    <input type="text" name="service_type" class="form-control border" placeholder="e.g. Vaccination" value="{{ request('service_type') }}">
                </div>
                <div class="col-12 text-end mt-3">
                    <a href="{{ route('reports.service-records') }}" class="btn btn-link btn-sm text-secondary text-decoration-none me-3">Reset Filters</a>
                    <button type="submit" class="btn btn-primary px-5">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        {{-- TABLE LAYOUT COPIED FROM DOCUMENTATION VIEW --}}
        <table class="table table-hover align-middle border bg-white">
            <thead class="bg-light">
                <tr class="text-secondary">
                    <th class="ps-3">Service Details</th>
                    <th>Beneficiary</th>
                    <th>Event</th>
                    <th>Service Provided</th>
                    <th>Health Provider</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr>
                    <td class="ps-3">
                        <div class="fw-bold text-primary">{{ $record->service_type ?? 'General Checkup' }}</div>
                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($record->service_date)->format('M d, Y') }}</small>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}</div>
                        <small class="text-muted">ID: #{{ $record->beneficiary_id }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-3">{{ $record->event->event_name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <div class="badge bg-primary-subtle text-primary border border-primary-subtle p-2">
                            {{ $record->service_type }}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 28px; height: 28px; font-size: 0.75rem; font-weight: bold;">{{ substr($record->providedBy->first_name ?? 'U', 0, 1) }}{{ substr($record->providedBy->last_name ?? 'N', 0, 1) }}</div>
                            <span class="small fw-medium text-secondary">{{ $record->providedBy->first_name ?? 'Unknown' }} {{ $record->providedBy->last_name ?? '' }}</span>
                        </div>
                    </td>
                    <td class="text-end pe-3">
                        {{-- Action copied from documentation layout --}}
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary btn-view-record" 
                                    data-diagnosis="{{ $record->diagnosis }}"
                                    data-treatment="{{ $record->treatment_given }}"
                                    data-patient="{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">No health service records match your criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Logic for Print alert
function showExportAlert(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'Generating Report',
        text: 'Please wait while we prepare the service records report...',
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

// Logic for View (Dx/Rx Modal)
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn-view-record');
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            const d = this.dataset;
            Swal.fire({
                title: '<h4 class="fw-bold text-primary mb-0">Service Details</h4>',
                html: `
                    <div class="text-start mt-4 border-top pt-3">
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase">Beneficiary</label>
                            <p class="mb-0 fs-6 text-dark fw-medium">${d.patient}</p>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase">Diagnosis (Dx)</label>
                            <p class="mb-0 p-2 bg-light rounded text-dark border">${d.diagnosis || 'No diagnosis recorded'}</p>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase">Treatment (Rx) & Remarks</label>
                            <p class="mb-0 p-2 bg-light rounded text-dark border">${d.treatment || 'No treatment recorded'}</p>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonColor: '#1e3a8a'
            });
        });
    });
});
</script>
@endsection