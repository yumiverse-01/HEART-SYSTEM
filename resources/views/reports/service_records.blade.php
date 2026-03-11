@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h3 class="mt-2 fw-bold text-dark">
                <i class="fas fa-hand-holding-medical text-primary me-2"></i>Service Records Report
            </h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.service-records.export.pdf', request()->query()) }}" onclick="showExportAlert(event, this.href)" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-file-pdf me-1"></i> Print
            </a>
            <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="fas fa-filter me-1"></i> Filters
            </button>
        </div>
    </div>

    <div class="collapse mb-4 {{ request()->hasAny(['date_from', 'event_id', 'service_type']) ? 'show' : '' }}" id="filterCollapse">
        <div class="card card-body border-0 shadow-sm bg-light p-4">
            <form method="GET" action="{{ route('reports.service-records') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Date From</label>
                    <input type="date" name="date_from" class="form-control border-0 shadow-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Date To</label>
                    <input type="date" name="date_to" class="form-control border-0 shadow-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Outreach Event</label>
                    <select name="event_id" class="form-select border-0 shadow-sm">
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
                    <input type="text" name="service_type" class="form-control border-0 shadow-sm" placeholder="e.g. Vaccination" value="{{ request('service_type') }}">
                </div>
                <div class="col-12 text-end mt-3">
                    <a href="{{ route('reports.service-records') }}" class="btn btn-link btn-sm text-secondary text-decoration-none me-3">Reset Filters</a>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th class="ps-4 py-3 small fw-bold text-uppercase">Date & Time</th>
                        <th class="small fw-bold text-uppercase">Beneficiary</th>
                        <th class="small fw-bold text-uppercase">Event</th>
                        <th class="small fw-bold text-uppercase">Service Provided</th>
                        <th class="small fw-bold text-uppercase">Health Provider</th>
                        <th class="text-end pe-4 small fw-bold text-uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($record->service_date)->format('M d, Y') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($record->service_date)->format('h:i A') }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}</div>
                            <small class="text-muted">ID: #{{ $record->beneficiary_id }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3">
                                {{ $record->event->event_name }}
                            </span>
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
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-white border shadow-sm btn-view-record" 
                                    data-diagnosis="{{ $record->diagnosis }}"
                                    data-treatment="{{ $record->treatment_given }}"
                                    data-patient="{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}">
                                <i class="fas fa-search-plus text-primary"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                            <p class="text-muted mb-0">No health service records match your search criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showExportAlert(event, url) {
    event.preventDefault(); // Stop the immediate download
    
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
                            <label class="small text-muted fw-bold text-uppercase">Diagnosis</label>
                            <p class="mb-0 p-2 bg-light rounded text-dark">${d.diagnosis || 'No diagnosis recorded'}</p>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase">Treatment Given & Remarks</label>
                            <p class="mb-0 p-2 bg-light rounded text-dark">${d.treatment || 'No treatment recorded'}</p>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonColor: '#0d6efd'
            });
        });
    });
});
</script>
@endsection