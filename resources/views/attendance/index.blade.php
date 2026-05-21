@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="page-header">
    <h3><i class="fas fa-clipboard-list me-2"></i>Attendance Sheet</h3>
    @if(request('event_id'))
        <a href="{{ route('attendance.index') }}" class="btn btn-md btn-outline-secondary">
            <i class="fas fa-undo me-1"></i> Clear Selection
        </a>
    @endif
</div>

{{-- Event Selector --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('attendance.index') }}">
            <div class="row g-2">
                <div class="col">
                    <select name="event_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Choose an Event to Start Marking --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->event_id }}" @if(request('event_id') == $event->event_id) selected @endif>
                                {{ $event->event_name }} ({{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}) - 
                                {{ $event->time_started ? \Carbon\Carbon::parse($event->time_started)->format('h:i A') : '--:--' }} to 
                                {{ $event->time_ended ? \Carbon\Carbon::parse($event->time_ended)->format('h:i A') : '--:--' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

@if(request('event_id'))

{{-- Beneficiary Filters --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('attendance.index') }}" id="beneficiaryFilterForm">
            <input type="hidden" name="event_id" value="{{ request('event_id') }}">
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Name, email, or contact..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="age_group" class="form-select">
                        <option value="">All Ages</option>
                        <option value="children" @if(request('age_group') == 'children') selected @endif>Children (≤17)</option>
                        <option value="youth"    @if(request('age_group') == 'youth')    selected @endif>Youth (18–30)</option>
                        <option value="adults"   @if(request('age_group') == 'adults')   selected @endif>Adults (31–59)</option>
                        <option value="senior"   @if(request('age_group') == 'senior')   selected @endif>Senior (≥60)</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="sex" class="form-select">
                        <option value="">All Genders</option>
                        <option value="Male"   @if(request('sex') == 'Male')   selected @endif>Male</option>
                        <option value="Female" @if(request('sex') == 'Female') selected @endif>Female</option>
                        <option value="Other"  @if(request('sex') == 'Other')  selected @endif>Other</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i></button>
                    @if(request('search') || request('age_group') || request('sex'))
                        <a href="{{ route('attendance.index', ['event_id' => request('event_id')]) }}"
                           class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Result summary --}}
<div class="d-flex justify-content-between align-items-center mb-2 px-1">
    <small class="text-muted fw-bold text-uppercase" style="font-size:0.75rem;">
        <i class="fas fa-users me-1"></i> Beneficiaries
        @if(request('search') || request('age_group') || request('sex'))
            <span class="text-muted fw-normal ms-1">(Filtered)</span>
        @endif
    </small>
    <small class="text-muted">{{ $beneficiaries->total() }} found</small>
</div>

{{-- Desktop Table --}}
<div class="table-card d-none d-md-block">
    <div class="table-responsive" style="overflow-x: auto;">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-secondary small text-uppercase">
                    <th class="ps-3">Beneficiary</th>
                    <th>Contact</th>
                    <th>Age / Sex</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beneficiaries as $b)
                    @php $attendance = $attendances->where('beneficiary_id', $b->beneficiary_id)->first(); @endphp
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-primary" style="text-transform: capitalize;">{{ $b->first_name }} {{ $b->last_name }}</div>
                            <small class="text-muted"><i class="far fa-envelope me-1"></i>{{ $b->email }}</small>
                        </td>
                        <td>
                            <small>{{ $b->contact_number ?? '—' }}</small>
                        </td>
                        <td class="small" style="text-transform: capitalize;">
                            {{ $b->age ?? '—' }} / {{ $b->sex ?? '—' }}
                        </td>
                        <td>
                            @if($attendance)
                                @if($attendance->attendance_status === 'Present')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Present
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>Absent
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-secondary">Not Marked</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm gap-1">
                                <!-- PRESENT BUTTON -->
                                <form action="{{ route('attendance.markAttendance') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="beneficiary_id" value="{{ $b->beneficiary_id }}">
                                    <input type="hidden" name="event_id" value="{{ request('event_id') }}">
                                    <input type="hidden" name="attendance_status" value="Present">
                                    <button type="submit" class="btn btn-sm {{ $attendance && $attendance->attendance_status === 'Present' ? 'btn-success' : 'btn-outline-success' }}" title="Mark as Present">
                                        Present
                                    </button>
                                </form>

                                <!-- ABSENT BUTTON -->
                                <form action="{{ route('attendance.markAttendance') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="beneficiary_id" value="{{ $b->beneficiary_id }}">
                                    <input type="hidden" name="event_id" value="{{ request('event_id') }}">
                                    <input type="hidden" name="attendance_status" value="Absent">
                                    <button type="submit" class="btn btn-sm {{ $attendance && $attendance->attendance_status === 'Absent' ? 'btn-danger' : 'btn-outline-danger' }}" title="Mark as Absent">
                                        Absent
                                    </button>
                                </form>
                            </div>
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

{{-- Mobile Card List --}}
<div class="d-md-none">
    @forelse($beneficiaries as $b)
        @php
            $attendance   = $attendances->where('beneficiary_id', $b->beneficiary_id)->first();
        @endphp
        <div class="table-card mb-2 p-3">
            <div style="min-width:0;">
                <div class="fw-bold text-primary text-truncate" style="text-transform: capitalize;">{{ $b->first_name }} {{ $b->last_name }}</div>
                <small class="text-muted d-block text-truncate">
                    <i class="far fa-envelope me-1"></i>{{ $b->email }}
                </small>
                <small class="text-muted d-block">
                    <i class="fas fa-phone me-1"></i>{{ $b->contact_number ?? '-' }} · {{ $b->age ?? '-' }} / {{ $b->sex ?? '-' }}
                </small>
                
                <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                    @if($attendance)
                        @if($attendance->attendance_status === 'Present')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Present
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>Absent
                            </span>
                        @endif
                    @else
                        <span class="badge bg-secondary">Not Marked</span>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 mt-3">
                    <!-- PRESENT -->
                    <form action="{{ route('attendance.markAttendance') }}" method="POST" class="flex-fill">
                        @csrf
                        <input type="hidden" name="beneficiary_id" value="{{ $b->beneficiary_id }}">
                        <input type="hidden" name="event_id" value="{{ request('event_id') }}">
                        <input type="hidden" name="attendance_status" value="Present">
                        <button type="submit" class="btn btn-sm w-100 {{ $attendance && $attendance->attendance_status === 'Present' ? 'btn-success' : 'btn-outline-success' }}">
                            Present
                        </button>
                    </form>

                    <!-- ABSENT -->
                    <form action="{{ route('attendance.markAttendance') }}" method="POST" class="flex-fill">
                        @csrf
                        <input type="hidden" name="beneficiary_id" value="{{ $b->beneficiary_id }}">
                        <input type="hidden" name="event_id" value="{{ request('event_id') }}">
                        <input type="hidden" name="attendance_status" value="Absent">
                        <button type="submit" class="btn btn-sm w-100 {{ $attendance && $attendance->attendance_status === 'Absent' ? 'btn-danger' : 'btn-outline-danger' }}">
                            Absent
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <p class="text-center text-muted py-4">No beneficiaries found.</p>
    @endforelse
</div>

{{-- Pagination --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
    <small class="text-secondary">
        Showing {{ $beneficiaries->firstItem() ?? 0 }}–{{ $beneficiaries->lastItem() ?? 0 }} of {{ $beneficiaries->total() }}
    </small>
    <div class="pagination-custom d-flex gap-1 flex-wrap">
        @if($beneficiaries->onFirstPage())
            <span class="btn btn-sm btn-light disabled border">Prev</span>
        @else
            <a href="{{ $beneficiaries->appends(request()->query())->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">Prev</a>
        @endif
        @foreach($beneficiaries->getUrlRange(max(1, $beneficiaries->currentPage() - 2), min($beneficiaries->lastPage(), $beneficiaries->currentPage() + 2)) as $page => $url)
            <a href="{{ $beneficiaries->appends(request()->query())->url($page) }}"
               class="btn btn-sm {{ $page == $beneficiaries->currentPage() ? 'btn-primary active' : 'btn-outline-primary' }} px-3">{{ $page }}</a>
        @endforeach
        @if($beneficiaries->hasMorePages())
            <a href="{{ $beneficiaries->appends(request()->query())->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">Next</a>
        @else
            <span class="btn btn-sm btn-light disabled border">Next</span>
        @endif
    </div>
</div>

@endif {{-- end @if(request('event_id')) --}}

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
        @if($errors->any())     showToast('error', @json($errors->all()).join(' ')); @endif
    });
</script>
@endpush

@endsection