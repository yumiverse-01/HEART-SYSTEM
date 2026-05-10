@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="page-header">
    <div>
        <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted">
            <i class="fas fa-arrow-left me-1"></i>Back to Reports
        </a>
        <h3 class="mt-1"><i class="fas fa-users me-2"></i>Beneficiary Registration Report</h3>
    </div>
    <div class="d-flex gap-2 flex-shrink-0">
        <a href="{{ route('reports.beneficiaries.export.pdf', request()->query()) }}"
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
            <form method="GET" action="{{ route('reports.beneficiaries') }}">
                <div class="row g-2">
                    <div class="col-6 col-md-4">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
                    </div>
                    <div class="col-6 col-md-4">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
                    </div>
                    <div class="col-12 col-md-4 d-flex gap-1">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-filter"></i></button>
                        <a href="{{ route('reports.beneficiaries') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
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
                    <th class="ps-3">Full Name</th>
                    <th>Profile</th>
                    <th>Contact Info</th>
                    <th class="text-end pe-3">Registered</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beneficiaries as $b)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:32px; height:32px; font-size:0.7rem; font-weight:600;">
                                    {{ strtoupper(substr($b->first_name, 0, 1)) }}{{ strtoupper(substr($b->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-primary">{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}</div>
                                    <small class="text-muted"><i class="far fa-envelope me-1"></i>{{ $b->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2">{{ $b->sex ?? 'N/A' }}</span>
                            <small class="d-block text-muted mt-1">{{ $b->age ?? '??' }} yrs old</small>
                        </td>
                        <td>
                            <div class="small"><i class="fas fa-phone text-muted me-1"></i>{{ $b->contact_number ?? '—' }}</div>
                            <div class="small text-truncate" style="max-width:160px;">
                                <i class="fas fa-shield-alt text-muted me-1"></i>{{ $b->guardian_name ?? 'No Guardian' }}
                            </div>
                        </td>
                        <td class="text-end pe-3">
                            <div class="fw-bold small">
                                {{ $b->date_registered ? \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') : '—' }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">No beneficiaries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Cards --}}
<div class="d-md-none">
    @forelse($beneficiaries as $b)
        <div class="table-card mb-2 p-3">
            <div class="d-flex align-items-start gap-2">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:32px; height:32px; font-size:0.7rem; font-weight:600;">
                    {{ strtoupper(substr($b->first_name, 0, 1)) }}{{ strtoupper(substr($b->last_name, 0, 1)) }}
                </div>
                <div style="min-width:0;">
                    <div class="fw-bold text-primary text-truncate">{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}</div>
                    <small class="text-muted d-block text-truncate"><i class="far fa-envelope me-1"></i>{{ $b->email }}</small>
                    <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                        <span class="badge bg-light text-dark border">{{ $b->sex ?? 'N/A' }}</span>
                        <small class="text-muted">{{ $b->age ?? '??' }} yrs</small>
                        <small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $b->contact_number ?? '—' }}</small>
                    </div>
                    <small class="text-muted d-block mt-1">
                        <i class="far fa-calendar-alt me-1"></i>
                        Registered: {{ $b->date_registered ? \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') : '—' }}
                    </small>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center text-muted py-4">No beneficiaries found.</p>
    @endforelse
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showExportAlert(e, url) {
        e.preventDefault();
        Swal.fire({
            title: 'Generating PDF',
            text: 'Please wait while we prepare the beneficiaries report...',
            icon: 'info', timer: 2000, timerProgressBar: true, showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); setTimeout(() => { window.location.href = url; }, 500); }
        });
    }
</script>
@endpush

@endsection