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
                <i class="fas fa-users text-primary me-2"></i>Beneficiary Registration Report
            </h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.beneficiaries.export.pdf', request()->query()) }}" onclick="showExportAlert(event, this.href)" class="btn btn-outline-secondary shadow-sm">
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
            <form method="GET" action="{{ route('reports.beneficiaries') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Registration Date From</label>
                    <input type="date" name="date_from" class="form-control border-0 shadow-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Registration Date To</label>
                    <input type="date" name="date_to" class="form-control border-0 shadow-sm" value="{{ request('date_to') }}">
                </div>
                
                {{-- Standardized Action Row --}}
                <div class="col-12 text-end mt-3">
                    <a href="{{ route('reports.beneficiaries') }}" class="btn btn-link btn-sm text-secondary text-decoration-none me-3">Reset Filters</a>
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
                        <th class="small fw-bold text-uppercase text-center">Sex</th>
                        <th class="small fw-bold text-uppercase text-center">Age</th>
                        <th class="small fw-bold text-uppercase">Contact Info</th>
                        <th class="small fw-bold text-uppercase">Guardian</th>
                        <th class="text-end pe-4 small fw-bold text-uppercase">Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beneficiaries as $b)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                {{-- Matching Avatar Style --}}
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 35px; height: 35px; font-size: 0.8rem; font-weight: bold;">
                                    {{ substr($b->first_name, 0, 1) }}{{ substr($b->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $b->last_name }}, {{ $b->first_name }}</div>
                                    <small class="text-muted">{{ $b->email ?? 'No email provided' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $b->sex ?? '—' }}
                            </span>
                        </td>
                        <td class="text-center text-dark fw-medium">{{ $b->age ?? '—' }}</td>
                        <td>
                            <div class="small"><i class="fas fa-phone-alt text-muted me-1"></i> {{ $b->contact_number ?? '—' }}</div>
                        </td>
                        <td>
                            <div class="small fw-medium text-dark">{{ $b->guardian_name ?? '—' }}</div>
                            <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Guardian</small>
                        </td>
                        <td class="text-end pe-4">
                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($b->date_registered)->format('h:i A') }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-user-slash fa-3x mb-3 text-light"></i>
                            <p class="text-muted mb-0">No registration records match your search criteria.</p>
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
    function showExportAlert(e, url) {
        e.preventDefault(); // Stop immediate navigation
        
        Swal.fire({
            title: 'Generating PDF',
            text: 'Please wait while we prepare the beneficiaries report...',
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