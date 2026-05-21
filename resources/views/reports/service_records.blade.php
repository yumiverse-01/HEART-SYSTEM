@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="page-header">
    <div>
        <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted">
            <i class="fas fa-arrow-left me-1"></i>Back to Reports
        </a>
        <h3 class="mt-1"><i class="fas fa-hand-holding-medical me-2"></i>Service Records Report</h3>
    </div>
    <div class="d-flex gap-2 flex-shrink-0">
        <a href="{{ route('reports.service-records.export.pdf', request()->query()) }}"
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
<div class="collapse mb-3 {{ request()->hasAny(['date_from', 'date_to', 'event_id', 'service_type']) ? 'show' : '' }}" id="filterCollapse">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('reports.service-records') }}">
                <div class="row g-2">
                    <div class="col-6 col-md-2">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="event_id" class="form-select">
                            <option value="">All Events</option>
                            @foreach($events as $event)
                                <option value="{{ $event->event_id }}" {{ request('event_id') == $event->event_id ? 'selected' : '' }}>
                                    {{ $event->event_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col col-md-3">
                        <input type="text" name="service_type" class="form-control"
                               placeholder="Service type..." value="{{ request('service_type') }}">
                    </div>
                    <div class="col-auto d-flex gap-1">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i></button>
                        <a href="{{ route('reports.service-records') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
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
                    <th class="ps-3">Service Details</th>
                    <th>Beneficiary</th>
                    <th>Event</th>
                    <th>Provider</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-primary" style="text-transform:uppercase">{{ $record->service_type ?? 'General Checkup' }}</div>
                            <small class="text-muted" style="text-transform:uppercase">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($record->service_date)->format('M d, Y') }}
                            </small>
                        </td>
                        <td>
                            <div class="fw-bold" style="text-transform:uppercase">{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}</div>
                            <small class="text-muted" style="text-transform:uppercase">ID: #{{ $record->beneficiary_id }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border" style="text-transform:uppercase">{{ $record->event->event_name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:28px; height:28px; font-size:0.65rem; font-weight:600;">
                                    {{ strtoupper(substr($record->providedBy->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($record->providedBy->last_name ?? 'N', 0, 1)) }}
                                </div>
                                <small class="text-secondary" style="text-transform:uppercase">{{ $record->providedBy->first_name ?? 'Unknown' }} {{ $record->providedBy->last_name ?? '' }}</small>
                            </div>
                        </td>
                        <td class="text-end pe-3">
                            <button class="btn btn-sm btn-outline-primary btn-view-record"
                                    data-diagnosis="{{ $record->diagnosis }}"
                                    data-treatment="{{ $record->treatment_given }}"
                                    data-patient="{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}">
                                <i class="fas fa-eye"></i>
                            </button>
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

{{-- Mobile Cards --}}
<div class="d-md-none">
    @forelse($records as $record)
        <div class="table-card mb-2 p-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div style="min-width:0;">
                    <div class="fw-bold text-primary text-truncate" style="text-transform:uppercase">{{ $record->service_type ?? 'General Checkup' }}</div>
                    <small class="text-muted d-block" style="text-transform:uppercase">
                        <i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($record->service_date)->format('M d, Y') }}
                    </small>
                    <small class="d-block mt-1 fw-bold" style="text-transform:uppercase">{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}</small>
                    <span class="badge bg-light text-dark border mt-1" style="text-transform:uppercase">{{ $record->event->event_name ?? 'N/A' }}</span>
                    <div class="mt-1 small text-truncate" style="text-transform:uppercase"><strong>Dx:</strong> {{ $record->diagnosis ?? 'None' }}</div>
                    <div class="small text-muted text-truncate" style="text-transform:uppercase"><strong>Rx:</strong> {{ $record->treatment_given ?? 'None' }}</div>
                </div>
                <button class="btn btn-sm btn-outline-primary flex-shrink-0 btn-view-record"
                        data-diagnosis="{{ $record->diagnosis }}"
                        data-treatment="{{ $record->treatment_given }}"
                        data-patient="{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
    @empty
        <p class="text-center text-muted py-4">No records found.</p>
    @endforelse
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showExportAlert(e, url) {
        e.preventDefault();
        Swal.fire({
            title: 'Generating Report',
            text: 'Please wait while we prepare the service records report...',
            icon: 'info', timer: 2000, timerProgressBar: true, showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); setTimeout(() => { window.location.href = url; }, 500); }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-view-record').forEach(btn => {
            btn.addEventListener('click', function () {
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
@endpush

@endsection