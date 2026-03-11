@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('reports.index') }}" class="text-decoration-none small text-muted"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h3 class="mt-2"><i class="fas fa-users text-primary"></i> Beneficiary Registration Report</h3>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="fas fa-print"></i></button>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
            <i class="fas fa-filter"></i> Filter Dates
        </button>
    </div>
</div>

<div class="collapse mb-4 {{ request()->hasAny(['date_from', 'date_to']) ? 'show' : '' }}" id="filterCollapse">
    <div class="card card-body border-0 shadow-sm bg-light">
        <form method="GET" action="{{ route('reports.beneficiaries') }}" class="row g-3">
            <div class="col-md-5">
                <label class="form-label small fw-bold">Registration Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold">Registration Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small">
                <tr>
                    <th class="ps-4 py-3">NAME</th>
                    <th>SEX</th>
                    <th>AGE</th>
                    <th>CONTACT</th>
                    <th>GUARDIAN</th>
                    <th class="text-end pe-4">REGISTRATION DATE</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beneficiaries as $b)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold">{{ $b->last_name }}, {{ $b->first_name }}</div>
                        <div class="text-muted small">{{ $b->email }}</div>
                    </td>
                    <td>{{ $b->sex ?? '—' }}</td>
                    <td>{{ $b->age ?? '—' }}</td>
                    <td>{{ $b->contact_number ?? '—' }}</td>
                    <td>{{ $b->guardian_name ?? '—' }}</td>
                    <td class="text-end pe-4">
                        <span class="badge bg-secondary-subtle text-secondary">
                            {{ \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No registration records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection