@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3><i class="fas fa-clipboard-list me-2"></i>Staff Activity Logs</h3>
</div>

{{-- Search & Filter --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('staff-activities.index') }}">
            <div class="row g-2">
                <div class="col-12 col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Search by activity or description..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col col-md-4">
                    <select name="type" class="form-select">
                        <option value="">All Modules</option>
                        <option value="Beneficiary"    {{ request('type') == 'Beneficiary'    ? 'selected' : '' }}>Beneficiary</option>
                        <option value="Outreach Event" {{ request('type') == 'Outreach Event' ? 'selected' : '' }}>Outreach Event</option>
                        <option value="Attendance"     {{ request('type') == 'Attendance'     ? 'selected' : '' }}>Attendance</option>
                        <option value="Service Record" {{ request('type') == 'Service Record' ? 'selected' : '' }}>Service Record</option>
                        <option value="Report"         {{ request('type') == 'Report'         ? 'selected' : '' }}>Report</option>
                        <option value="User Management"{{ request('type') == 'User Management'? 'selected' : '' }}>User Management</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Desktop table --}}
<div class="table-card d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-secondary small text-uppercase">
                    <th class="ps-3">Timestamp</th>
                    <th>Staff Member</th>
                    <th>Activity</th>
                    <th>Module</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($log->timestamp)->format('M d, Y') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($log->timestamp)->format('h:i A') }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:32px; height:32px; font-size:0.7rem; font-weight:600;">
                                    {{ strtoupper(substr($log->user->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($log->user->last_name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold small">{{ $log->user->first_name ?? 'System' }} {{ $log->user->last_name ?? '' }}</div>
                                    <small class="text-muted" style="font-size:0.7rem;">ID: #{{ $log->user_id }}</small>
                                </div>
                            </div>
                        </td>
                        <td style="max-width:280px;">
                            <div class="fw-bold text-primary">{{ $log->activity_name }}</div>
                            <small class="text-muted text-truncate d-block">{{ $log->description }}</small>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($log->module) {
                                    'Create' => 'bg-success-subtle text-success border-success-subtle',
                                    'Update' => 'bg-info-subtle text-info border-info-subtle',
                                    'Delete' => 'bg-danger-subtle text-danger border-danger-subtle',
                                    default  => 'bg-light text-dark border-secondary-subtle'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} border px-3 rounded-pill">{{ $log->module }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">No activity logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile card list --}}
<div class="d-md-none">
    @forelse($logs as $log)
        @php
            $badgeClass = match($log->module) {
                'Create' => 'bg-success-subtle text-success border-success-subtle',
                'Update' => 'bg-info-subtle text-info border-info-subtle',
                'Delete' => 'bg-danger-subtle text-danger border-danger-subtle',
                default  => 'bg-light text-dark border-secondary-subtle'
            };
        @endphp
        <div class="table-card mb-2 p-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div style="min-width:0;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:30px; height:30px; font-size:0.65rem; font-weight:600;">
                            {{ strtoupper(substr($log->user->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($log->user->last_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold small">{{ $log->user->first_name ?? 'System' }} {{ $log->user->last_name ?? '' }}</div>
                            <small class="text-muted" style="font-size:0.7rem;">ID: #{{ $log->user_id }}</small>
                        </div>
                    </div>
                    <div class="fw-bold text-primary text-truncate">{{ $log->activity_name }}</div>
                    <small class="text-muted d-block text-truncate">{{ $log->description }}</small>
                    <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                        <span class="badge {{ $badgeClass }} border px-2 rounded-pill">{{ $log->module }}</span>
                        <small class="text-muted">
                            <i class="far fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($log->timestamp)->format('M d, Y · h:i A') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center text-muted py-4">No activity logs found.</p>
    @endforelse
</div>

{{-- Pagination --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
    <small class="text-secondary">
        Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }}
    </small>
    <div class="pagination-custom d-flex gap-1 flex-wrap">
        @if($logs->onFirstPage())
            <span class="btn btn-sm btn-light disabled border">Prev</span>
        @else
            <a href="{{ $logs->appends(request()->query())->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">Prev</a>
        @endif
        @foreach($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
            <a href="{{ $logs->appends(request()->query())->url($page) }}"
               class="btn btn-sm {{ $page == $logs->currentPage() ? 'btn-primary active' : 'btn-outline-primary' }} px-3">{{ $page }}</a>
        @endforeach
        @if($logs->hasMorePages())
            <a href="{{ $logs->appends(request()->query())->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">Next</a>
        @else
            <span class="btn btn-sm btn-light disabled border">Next</span>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showToast(type, message) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            customClass: { popup: 'colored-toast' }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success')) showToast('success', '{{ session("success") }}'); @endif
        @if(session('error'))   showToast('error', '{{ session("error") }}'); @endif
    });
</script>
@endpush

@endsection