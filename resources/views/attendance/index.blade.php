@extends('layouts.app')

@section('content')

<h3><i class="fas fa-clipboard-list"></i> Attendance Sheet</h3>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Select Event</label>
                    <select name="event_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Choose an Event --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->event_id }}"
                            @if(request('event_id') == $event->event_id) selected @endif>
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
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Mark Attendance</h5>
        </div>
        <div class="card-body">
            @if($beneficiaries->count() > 0)
                <div class="row g-4">
                    @foreach($beneficiaries as $b)
                        @php
                            $attendance = $attendances
                                ->where('beneficiary_id', $b->beneficiary_id)
                                ->where('event_id', request('event_id'))
                                ->first();
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}</h6>
                                    <p class="card-text mb-2">
                                        Status:
                                        @if($attendance)
                                            @if($attendance->attendance_status == 'Present')
                                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Present</span>
                                            @else
                                                <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Absent</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-question-circle"></i> Not Marked</span>
                                        @endif
                                    </p>
                                    <div class="mt-auto text-end">
                                        <button class="btn btn-primary btn-sm btn-mark-attendance"
                                                data-beneficiary_id="{{ $b->beneficiary_id }}"
                                                data-event_id="{{ request('event_id') }}"
                                                data-status="{{ optional($attendance)->attendance_status }}">
                                            <i class="fas fa-clipboard-check"></i> Mark
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted mb-0">No beneficiaries found for this event</p>
                </div>
            @endif
        </div>
    </div>
@endif

<!-- attendance modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="attendanceForm" method="POST" action="{{ route('attendance.mark') }}">
                @csrf
                <input type="hidden" name="beneficiary_id" id="modal_beneficiary_id">
                <input type="hidden" name="event_id" id="modal_event_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">Mark Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="attendance_status" id="modal_status" class="form-select" required>
                            <option value="">Select</option>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time In</label>
                        <input type="time" name="time_in" id="modal_time_in" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time Out</label>
                        <input type="time" name="time_out" id="modal_time_out" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const attendanceModal = new bootstrap.Modal(document.getElementById('attendanceModal'));

    document.querySelectorAll('.btn-mark-attendance').forEach(btn => {
        btn.addEventListener('click', () => {
            const data = btn.dataset;
            document.getElementById('modal_beneficiary_id').value = data.beneficiary_id;
            document.getElementById('modal_event_id').value = data.event_id;
            document.getElementById('modal_status').value = data.status || '';
            document.getElementById('modal_time_in').value = '';
            document.getElementById('modal_time_out').value = '';
            attendanceModal.show();
        });
    });
</script>
@endpush

@endsection