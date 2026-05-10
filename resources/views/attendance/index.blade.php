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
                                {{ $event->event_name }} ({{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }})
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
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-secondary small text-uppercase">
                    <th class="ps-3">Beneficiary</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beneficiaries as $b)
                    @php $attendance = $attendances->where('beneficiary_id', $b->beneficiary_id)->first(); @endphp
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-primary">{{ $b->first_name }} {{ $b->last_name }}</div>
                            <small class="text-muted"><i class="far fa-envelope me-1"></i>{{ $b->email }}</small>
                        </td>
                        <td class="small">
                            {{ $attendance && $attendance->time_in  ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A')  : '—' }}
                        </td>
                        <td class="small">
                            {{ $attendance && $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : '—' }}
                        </td>
                        <td>
                            @php
                                $statusClass = $attendance
                                    ? ($attendance->attendance_status == 'Present' ? 'bg-success' : 'bg-danger')
                                    : 'bg-secondary';
                            @endphp
                            <span class="badge {{ $statusClass }} btn-mark-attendance px-3 py-2"
                                  style="cursor:pointer; font-weight:500;"
                                  data-beneficiary_id="{{ $b->beneficiary_id }}"
                                  data-name="{{ $b->first_name }} {{ $b->last_name }}"
                                  data-status="{{ optional($attendance)->attendance_status }}"
                                  data-time_in="{{ optional($attendance)->time_in }}"
                                  data-time_out="{{ optional($attendance)->time_out }}">
                                {{ $attendance ? $attendance->attendance_status : 'Not Marked' }}
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-info btn-view-beneficiary"
                                        data-full_name="{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}"
                                        data-email="{{ $b->email }}"
                                        data-age="{{ $b->age ?? 'N/A' }}"
                                        data-sex="{{ $b->sex ?? 'N/A' }}"
                                        data-address="{{ $b->address ?? 'N/A' }}"
                                        data-contact="{{ $b->contact_number ?? 'N/A' }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary btn-mark-attendance"
                                        data-beneficiary_id="{{ $b->beneficiary_id }}"
                                        data-name="{{ $b->first_name }} {{ $b->last_name }}"
                                        data-status="{{ optional($attendance)->attendance_status }}"
                                        data-time_in="{{ optional($attendance)->time_in }}"
                                        data-time_out="{{ optional($attendance)->time_out }}">
                                    <i class="fas fa-edit"></i>
                                </button>
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
            $statusClass  = $attendance
                ? ($attendance->attendance_status == 'Present' ? 'bg-success' : 'bg-danger')
                : 'bg-secondary';
            $statusLabel  = $attendance ? $attendance->attendance_status : 'Not Marked';
        @endphp
        <div class="table-card mb-2 p-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div style="min-width:0;">
                    <div class="fw-bold text-primary text-truncate">{{ $b->first_name }} {{ $b->last_name }}</div>
                    <small class="text-muted d-block text-truncate">
                        <i class="far fa-envelope me-1"></i>{{ $b->email }}
                    </small>
                    <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                        <span class="badge {{ $statusClass }} btn-mark-attendance px-3 py-2"
                              style="cursor:pointer; font-weight:500;"
                              data-beneficiary_id="{{ $b->beneficiary_id }}"
                              data-name="{{ $b->first_name }} {{ $b->last_name }}"
                              data-status="{{ optional($attendance)->attendance_status }}"
                              data-time_in="{{ optional($attendance)->time_in }}"
                              data-time_out="{{ optional($attendance)->time_out }}">
                            {{ $statusLabel }}
                        </span>
                        @if($attendance && $attendance->time_in)
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                In: {{ \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') }}
                                @if($attendance->time_out)
                                    · Out: {{ \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}
                                @endif
                            </small>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-1 flex-shrink-0">
                    <button class="btn btn-sm btn-outline-info btn-view-beneficiary"
                            data-full_name="{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}"
                            data-email="{{ $b->email }}"
                            data-age="{{ $b->age ?? 'N/A' }}"
                            data-sex="{{ $b->sex ?? 'N/A' }}"
                            data-address="{{ $b->address ?? 'N/A' }}"
                            data-contact="{{ $b->contact_number ?? 'N/A' }}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-primary btn-mark-attendance"
                            data-beneficiary_id="{{ $b->beneficiary_id }}"
                            data-name="{{ $b->first_name }} {{ $b->last_name }}"
                            data-status="{{ optional($attendance)->attendance_status }}"
                            data-time_in="{{ optional($attendance)->time_in }}"
                            data-time_out="{{ optional($attendance)->time_out }}">
                        <i class="fas fa-edit"></i>
                    </button>
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

{{-- Mark Attendance Modal --}}
<div class="modal fade" id="attendanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" style="color:#1e3a8a;">Mark Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="attendanceForm" method="POST" action="{{ route('attendance.mark') }}">
                @csrf
                <input type="hidden" name="beneficiary_id" id="modal_beneficiary_id">
                <input type="hidden" name="event_id" value="{{ request('event_id') }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Beneficiary</label>
                            <input type="text" id="display_name" class="form-control bg-light" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="attendance_status" id="modal_status" class="form-select" required>
                                <option value="">-- Select Status --</option>
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Time In</label>
                            <input type="time" name="time_in" id="modal_time_in" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Time Out</label>
                            <input type="time" name="time_out" id="modal_time_out" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- View Beneficiary Modal --}}
<div class="modal fade" id="viewBeneficiaryModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" style="color:#1e3a8a;">
                    <i class="fas fa-user-circle me-2"></i>Beneficiary Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="text-muted small d-block">Full Name</label>
                        <div id="view_name" class="fw-bold fs-5"></div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-muted small d-block">Email</label>
                        <div id="view_email" class="text-dark"></div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="text-muted small d-block">Contact</label>
                        <div id="view_contact"></div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="text-muted small d-block">Age / Sex</label>
                        <div id="view_age_sex"></div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block border-bottom pb-1 mb-2">Full Address</label>
                        <div id="view_address" class="small"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const attendanceModal      = new bootstrap.Modal(document.getElementById('attendanceModal'));
    const viewBeneficiaryModal = new bootstrap.Modal(document.getElementById('viewBeneficiaryModal'));

    // View beneficiary
    document.querySelectorAll('.btn-view-beneficiary').forEach(btn => {
        btn.addEventListener('click', function () {
            const d = this.dataset;
            document.getElementById('view_name').textContent    = d.full_name;
            document.getElementById('view_email').textContent   = d.email;
            document.getElementById('view_contact').textContent = d.contact;
            document.getElementById('view_age_sex').textContent = `${d.age} / ${d.sex}`;
            document.getElementById('view_address').textContent = d.address;
            viewBeneficiaryModal.show();
        });
    });

    // Mark attendance
    document.querySelectorAll('.btn-mark-attendance').forEach(btn => {
        btn.addEventListener('click', function () {
            const d = this.dataset;
            const showForm = () => {
                document.getElementById('modal_beneficiary_id').value = d.beneficiary_id;
                document.getElementById('display_name').value         = d.name;
                document.getElementById('modal_status').value         = d.status || '';
                document.getElementById('modal_time_in').value        = d.time_in  ? d.time_in.substring(0, 5)  : '';
                document.getElementById('modal_time_out').value       = d.time_out ? d.time_out.substring(0, 5) : '';
                attendanceModal.show();
            };

            if (d.status === 'Present' || d.status === 'Absent') {
                Swal.fire({
                    title: 'Update Record?',
                    text: `Already marked as "${d.status}". Update it?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: 'Yes, update it!'
                }).then(r => { if (r.isConfirmed) showForm(); });
            } else {
                showForm();
            }
        });
    });

    // Session flash messages
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success')) Swal.fire({ title: 'Success!', text: '{{ session("success") }}', icon: 'success', confirmButtonColor: '#1e3a8a' }); @endif
        @if(session('error'))   Swal.fire({ title: 'Error!',   text: '{{ session("error") }}',   icon: 'error',   confirmButtonColor: '#d33'    }); @endif
        @if($errors->any())     Swal.fire({ title: 'Validation Error!', html: @json($errors->all()).join('<br>'), icon: 'error', confirmButtonColor: '#d33' }); @endif
    });
</script>
@endpush

@endsection