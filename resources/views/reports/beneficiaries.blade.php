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
                <i class="fas fa-users me-2"></i>Beneficiary Registration Report
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
                    <input type="date" name="date_from" class="form-control border" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Registration Date To</label>
                    <input type="date" name="date_to" class="form-control border" value="{{ request('date_to') }}">
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
    <div class="table-responsive">
        <table class="table table-hover align-middle shadow-sm bg-white">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="ps-3">Full Name</th>
                    <th>Profile</th>
                    <th>Contact Info</th>
                    <th>Registration</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beneficiaries as $b)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; font-size: 0.8rem; font-weight: bold;">
                                    {{ substr($b->first_name, 0, 1) }}{{ substr($b->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-primary">{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}</div>
                                    <small class="text-muted"><i class="far fa-envelope me-1"></i> {{ $b->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2">{{ $b->sex ?? 'N/A' }}</span>
                            <small class="d-block text-muted mt-1">{{ $b->age ?? '??' }} years old</small>
                        </td>
                        <td>
                            <div class="small"><i class="fas fa-phone text-muted me-1"></i> {{ $b->contact_number ?? '-' }}</div>
                            <div class="small text-truncate" style="max-width: 150px;"><i class="fas fa-shield-alt text-muted me-1"></i> {{ $b->guardian_name ?? 'No Guardian' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold small">{{ $b->date_registered ? \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') : '-' }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No beneficiaries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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