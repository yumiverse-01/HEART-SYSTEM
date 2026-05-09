@extends('layouts.app')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <h3 class="fw-bold m-0"><i class="fas fa-clipboard-list me-2"></i>Attendance Sheet</h3>
    @if(request('event_id'))
        <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="fas fa-undo me-1"></i> Clear Selection
        </a>
    @endif
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('attendance.index') }}" id="eventFilterForm">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-bold text-muted text-uppercase">Choose an Event</label>
                    <select name="event_id" class="form-select shadow-sm" onchange="this.form.submit()">
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
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white mb-0">
            <thead class="bg-light">
                <tr class="text-secondary small text-uppercase">
                    <th class="ps-3">Beneficiary Name</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beneficiaries as $b)
                    @php
                        $attendance = $attendances->where('beneficiary_id', $b->beneficiary_id)->first();
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-primary">{{ $b->first_name }} {{ $b->last_name }}</div>
                            <small class="text-muted"><i class="far fa-envelope me-1"></i> {{ $b->email }}</small>
                        </td>
                        <td class="small">{{ $attendance && $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : '—' }}</td>
                        <td class="small">{{ $attendance && $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : '—' }}</td>
                        <td>
                            <span class="badge {{ $attendance && $attendance->attendance_status == 'Present' ? 'bg-success' : ($attendance ? 'bg-danger' : 'bg-secondary') }} btn-mark-attendance px-3 py-2" 
                                  style="cursor: pointer; font-weight: 500;"
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
                                <button class="btn btn-sm btn-light border btn-view-beneficiary"
                                        data-full_name="{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}"
                                        data-email="{{ $b->email }}"
                                        data-birth_date="{{ $b->birth_date ? \Carbon\Carbon::parse($b->birth_date)->format('M d, Y') : 'N/A' }}"
                                        data-age="{{ $b->age ?? 'N/A' }}"
                                        data-sex="{{ $b->sex ?? 'N/A' }}"
                                        data-address="{{ $b->address ?? 'N/A' }}"
                                        data-contact="{{ $b->contact_number ?? 'N/A' }}"
                                        data-guardian="{{ $b->guardian_name ?? 'N/A' }}"
                                        data-registered="{{ $b->date_registered ? \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') : 'N/A' }}">
                                    <i class="fas fa-eye text-info"></i>
                                </button>
                                <button class="btn btn-sm btn-light border btn-mark-attendance"
                                        data-beneficiary_id="{{ $b->beneficiary_id }}"
                                        data-name="{{ $b->first_name }} {{ $b->last_name }}"
                                        data-status="{{ optional($attendance)->attendance_status }}"
                                        data-time_in="{{ optional($attendance)->time_in }}"
                                        data-time_out="{{ optional($attendance)->time_out }}">
                                    <i class="fas fa-edit text-primary"></i>
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

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 px-2 gap-3">
    <div class="text-secondary small">
        Showing <strong>{{ $beneficiaries->firstItem() ?? 0 }}</strong> to <strong>{{ $beneficiaries->lastItem() ?? 0 }}</strong> of <strong>{{ $beneficiaries->total() }}</strong>
    </div>
    <div class="pagination-custom d-flex gap-1 flex-wrap justify-content-center">
        @if ($beneficiaries->onFirstPage())
            <span class="btn btn-sm btn-light disabled border">Previous</span>
        @else
            <a href="{{ $beneficiaries->appends(request()->query())->previousPageUrl() }}" class="btn btn-sm btn-outline-primary shadow-sm">Previous</a>
        @endif

        @foreach ($beneficiaries->getUrlRange(max(1, $beneficiaries->currentPage() - 1), min($beneficiaries->lastPage(), $beneficiaries->currentPage() + 1)) as $page => $url)
            <a href="{{ $beneficiaries->appends(request()->query())->url($page) }}" class="btn btn-sm {{ $page == $beneficiaries->currentPage() ? 'btn-primary active' : 'btn-outline-primary' }} px-3">{{ $page }}</a>
        @endforeach

        @if ($beneficiaries->hasMorePages())
            <a href="{{ $beneficiaries->appends(request()->query())->nextPageUrl() }}" class="btn btn-sm btn-outline-primary shadow-sm">Next</a>
        @else
            <span class="btn btn-sm btn-light disabled border">Next</span>
        @endif
    </div>
</div>
@endif

{{-- ATTENDANCE MODAL --}}
<div class="modal fade" id="attendanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" style="color: #1e3a8a;">Mark Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="attendanceForm" method="POST" action="{{ route('attendance.mark') }}">
                @csrf
                <input type="hidden" name="beneficiary_id" id="modal_beneficiary_id">
                <input type="hidden" name="event_id" value="{{ request('event_id') }}">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Beneficiary</label>
                        <input type="text" id="display_name" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="attendance_status" id="modal_status" class="form-select shadow-sm" required>
                            <option value="">-- Select Status --</option>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Time In</label>
                            <input type="time" name="time_in" id="modal_time_in" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Time Out</label>
                            <input type="time" name="time_out" id="modal_time_out" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="background-color: #1e3a8a;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- VIEW MODAL --}}
<div class="modal fade" id="viewBeneficiaryModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" style="color: #1e3a8a;"><i class="fas fa-user-circle me-2"></i> Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Full Name</label>
                        <div id="view_name" class="fw-bold fs-5"></div>
                    </div>
                    <div class="col-md-6">
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
                    <div class="col-12 mt-3">
                        <label class="text-muted small d-block border-bottom pb-1 mb-2">Full Address</label>
                        <div id="view_address" class="small"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const attendanceModal = new bootstrap.Modal(document.getElementById('attendanceModal'));
    const viewModal = new bootstrap.Modal(document.getElementById('viewBeneficiaryModal'));

    document.querySelectorAll('.btn-view-beneficiary').forEach(btn => {
        btn.addEventListener('click', function() {
            const d = this.dataset;
            document.getElementById('view_name').textContent = d.full_name;
            document.getElementById('view_email').textContent = d.email;
            document.getElementById('view_contact').textContent = d.contact;
            document.getElementById('view_age_sex').textContent = `${d.age} / ${d.sex}`;
            document.getElementById('view_address').textContent = d.address;
            viewModal.show();
        });
    });

    document.querySelectorAll('.btn-mark-attendance').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            const showForm = () => {
                document.getElementById('modal_beneficiary_id').value = data.beneficiary_id;
                document.getElementById('display_name').value = data.name;
                document.getElementById('modal_status').value = data.status || '';
                document.getElementById('modal_time_in').value = data.time_in ? data.time_in.substring(0,5) : '';
                document.getElementById('modal_time_out').value = data.time_out ? data.time_out.substring(0,5) : '';
                attendanceModal.show();
            };

            if (data.status === 'Present' || data.status === 'Absent') {
                Swal.fire({
                    title: 'Update Record?',
                    text: `Already marked as "${data.status}". Update it?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: 'Yes, update it!'
                }).then((result) => { if (result.isConfirmed) showForm(); });
            } else {
                showForm();
            }
        });
    });
</script>
@endpush
@endsection