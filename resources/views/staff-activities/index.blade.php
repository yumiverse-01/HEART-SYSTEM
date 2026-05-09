@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0"><i class="fas fa-clipboard-list text-navy me-2"></i>Staff Activity Logs</h3>
            <p class="text-muted small mb-0">Monitor system changes and staff performance records</p>
        </div>
        <!-- <div>
            <button onclick="window.print()" class="btn btn-outline-secondary border bg-white">
                <i class="fas fa-print me-1"></i> Print Log
            </button>
        </div> -->
    </div>

    {{-- Search and Quick Filter --}}
    <div class="card mb-4 border bg-white" style="box-shadow: none;">
        <div class="card-body">
            <form method="GET" action="{{ route('staff-activities.index') }}" class="row g-2">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by activity or description..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select border">
                        <option value="">All Modules</option>
                        <option value="Beneficiary" {{ request('type') == 'Beneficiary' ? 'selected' : '' }}>Beneficiary</option>
                        <option value="Outreach Event" {{ request('type') == 'Outreach Event' ? 'selected' : '' }}>Outreach Event</option>
                        <option value="Attendance" {{ request('type') == 'Attendance' ? 'selected' : '' }}>Attendance</option>
                        <option value="Service Record" {{ request('type') == 'Service Record' ? 'selected' : '' }}>Service Record</option>
                        <option value="Report" {{ request('type') == 'Report' ? 'selected' : '' }}>Report</option>
                        <option value="User Management" {{ request('type') == 'User Management' ? 'selected' : '' }}>User Management</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        {{-- Consistent Table Layout --}}
        <table class="table table-hover align-middle border bg-white mb-0">
            <thead class="bg-light">
                <tr class="text-secondary">
                    <th class="ps-3 py-3 small fw-bold text-uppercase">Log Timestamp</th>
                    <th class="small fw-bold text-uppercase">Staff Member</th>
                    <th class="small fw-bold text-uppercase">Activity</th>
                    <th class="small fw-bold text-uppercase">Module</th>
                    <!-- <th class="text-end pe-3 small fw-bold text-uppercase">Action</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-bottom">
                    <td class="ps-3">
                        <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($log->timestamp)->format('M d, Y') }}</div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($log->timestamp)->format('h:i A') }}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 0.7rem; font-weight: bold;">
                                {{ substr($log->user->first_name ?? 'S', 0, 1) }}{{ substr($log->user->last_name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold small">{{ $log->user->first_name ?? 'System' }} {{ $log->user->last_name ?? '' }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ $log->user_id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-primary">{{ $log->activity_name }}</div>
                        <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ $log->description }}</small>
                    </td>
                    <td>
                        @php
                            $badgeColor = match($log->module) {
                                'Create' => 'bg-success-subtle text-success border-success-subtle',
                                'Update' => 'bg-info-subtle text-info border-info-subtle',
                                'Delete' => 'bg-danger-subtle text-danger border-danger-subtle',
                                default => 'bg-light text-dark border-secondary-subtle'
                            };
                        @endphp
                        <span class="badge {{ $badgeColor }} border px-3 rounded-pill">
                            {{ $log->module }}
                        </span>
                    </td>
                    <!-- <td class="text-end pe-3">
                        <button class="btn btn-sm btn-outline-primary border btn-view-log" 
                                data-details="{{ $log->activity_details }}"
                                data-provided="{{ $log->providedBy->first_name ?? 'N/A' }} {{ $log->providedBy->last_name ?? '' }}"
                                data-date="{{ $log->service_date }}">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </td> -->
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted border-0">No activity logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-4 px-2">
        <div class="text-secondary small">
            Showing <strong>{{ $logs->firstItem() ?? 0 }}</strong> to <strong>{{ $logs->lastItem() ?? 0 }}</strong> of <strong>{{ $logs->total() }}</strong> entries
        </div>
        <div class="pagination-custom d-flex gap-1">
            @if ($logs->onFirstPage())
                <span class="btn btn-sm btn-light disabled border">Previous</span>
            @else
                <a href="{{ $logs->appends(request()->query())->previousPageUrl() }}" class="btn btn-sm btn-outline-primary shadow-sm">Previous</a>
            @endif
            @foreach ($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
                <a href="{{ $logs->appends(request()->query())->url($page) }}" class="btn btn-sm {{ $page == $logs->currentPage() ? 'btn-primary active' : 'btn-outline-primary' }} px-3">{{ $page }}</a>
            @endforeach
            @if ($logs->hasMorePages())
                <a href="{{ $logs->appends(request()->query())->nextPageUrl() }}" class="btn btn-sm btn-outline-primary shadow-sm">Next</a>
            @else
                <span class="btn btn-sm btn-light disabled border">Next</span>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn-view-log');
    
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            const d = this.dataset;
            Swal.fire({
                title: '<h4 class="fw-bold text-primary mb-0">Activity Details</h4>',
                html: `
                    <div class="text-start mt-4 border-top pt-3">
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase">Technical Details</label>
                            <div class="p-3 bg-light rounded font-monospace border small text-dark" style="white-space: pre-wrap;">${d.details || 'No additional details.'}</div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="small text-muted fw-bold text-uppercase">Provided By</label>
                                <p class="mb-0 fw-medium text-dark border-bottom pb-1">${d.provided}</p>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold text-uppercase">Service Date</label>
                                <p class="mb-0 fw-medium text-dark border-bottom pb-1">${d.date || 'N/A'}</p>
                            </div>
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