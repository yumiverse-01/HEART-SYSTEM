@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3><i class="fas fa-calendar-alt me-2"></i>Outreach Events</h3>
    <button class="btn btn-primary px-4" id="btnOpenCreateEvent">
        <i class="fas fa-plus me-2"></i> Create Event
    </button>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form action="{{ route('events.index') }}" method="GET">
            <div class="row g-2">
                <div class="col-12 col-sm-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Event name or location..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-sm-4">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Upcoming"  {{ request('status')=='Upcoming'  ? 'selected':'' }}>Upcoming</option>
                        <option value="Completed" {{ request('status')=='Completed' ? 'selected':'' }}>Completed</option>
                        <option value="Cancelled" {{ request('status')=='Cancelled' ? 'selected':'' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-6 col-sm-2">
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
                    <th class="ps-3">Event Details</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-primary">{{ $event->event_name }}</div>
                            <small class="text-muted">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : '-' }}
                            </small>
                        </td>
                        <td><span class="badge bg-light text-dark border px-2">{{ $event->event_type ?? 'General' }}</span></td>
                        <td><small>{{ $event->location ?? '-' }}</small></td>
                        <td>
                            @php $bc = match($event->status) { 'Upcoming'=>'bg-warning text-dark','Completed'=>'bg-success text-white','Cancelled'=>'bg-danger text-white',default=>'bg-secondary text-white' }; @endphp
                            <span class="badge {{ $bc }}">{{ $event->status }}</span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary btn-edit-event"
                                    data-event_id="{{ $event->event_id }}"
                                    data-event_name="{{ $event->event_name }}"
                                    data-event_type="{{ $event->event_type }}"
                                    data-event_date="{{ $event->event_date }}"
                                    data-location="{{ $event->location }}"
                                    data-description="{{ $event->description }}"
                                    data-status="{{ $event->status }}"
                                    data-time_started="{{ $event->time_started }}"
                                    data-time_ended="{{ $event->time_ended }}"
                                    >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteEvent({{ $event->event_id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">No outreach events found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile card list --}}
<div class="d-md-none">
    @forelse($events as $event)
    <div class="table-card mb-2 p-3">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <div style="min-width:0;">
                <div class="fw-bold text-primary text-truncate">{{ $event->event_name }}</div>
                <small class="text-muted d-block">
                    <i class="far fa-calendar-alt me-1"></i>
                    {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : '-' }}
                </small>
                <small class="text-muted d-block text-truncate">
                    <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location ?? '-' }}
                </small>
                <div class="mt-1">
                    @php $bc = match($event->status) { 'Upcoming'=>'bg-warning text-dark','Completed'=>'bg-success text-white','Cancelled'=>'bg-danger text-white',default=>'bg-secondary text-white' }; @endphp
                    <span class="badge {{ $bc }} me-1">{{ $event->status }}</span>
                    <span class="badge bg-light text-dark border">{{ $event->event_type ?? 'General' }}</span>
                </div>
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
                <button class="btn btn-sm btn-outline-primary btn-edit-event"
                    data-event_id="{{ $event->event_id }}"
                    data-event_name="{{ $event->event_name }}"
                    data-event_type="{{ $event->event_type }}"
                    data-event_date="{{ $event->event_date }}"
                    data-location="{{ $event->location }}"
                    data-description="{{ $event->description }}"
                    data-status="{{ $event->status }}"
                    data-time_started="{{ $event->time_started }}"
                    data-time_ended="{{ $event->time_ended }}"
                    >
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteEvent({{ $event->event_id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
        <p class="text-center text-muted py-4">No outreach events found.</p>
    @endforelse
</div>

{{-- Pagination --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
    <small class="text-secondary">
        Showing {{ $events->firstItem() ?? 0 }}–{{ $events->lastItem() ?? 0 }} of {{ $events->total() }}
    </small>
    <div class="pagination-custom d-flex gap-1 flex-wrap">
        @if($events->onFirstPage())
            <span class="btn btn-sm btn-light disabled border">Prev</span>
        @else
            <a href="{{ $events->appends(request()->query())->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">Prev</a>
        @endif
        @foreach($events->getUrlRange(max(1,$events->currentPage()-2),min($events->lastPage(),$events->currentPage()+2)) as $page => $url)
            <a href="{{ $events->appends(request()->query())->url($page) }}"
               class="btn btn-sm {{ $page==$events->currentPage()?'btn-primary active':'btn-outline-primary' }} px-3">{{ $page }}</a>
        @endforeach
        @if($events->hasMorePages())
            <a href="{{ $events->appends(request()->query())->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">Next</a>
        @else
            <span class="btn btn-sm btn-light disabled border">Next</span>
        @endif
    </div>
</div>

@foreach($events as $event)
    <form id="delete-form-{{ $event->event_id }}"
          action="{{ route('events.destroy', $event->event_id) }}"
          method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach

{{-- Modal --}}
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="eventModalLabel">Create Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eventForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="eventFormMethod">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Event Name <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" id="event_name" class="form-control" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Type</label>
                            <input type="text" name="event_type" id="event_type" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="event_date" id="event_date" class="form-control" required>
                        </div>
                        <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Time Started <span class="text-danger">*</span></label>
                            <input type="time" name="time_started" id="time_started" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Time Ended <span class="text-danger">*</span></label>
                            <input type="time" name="time_ended" id="time_ended" class="form-control" required>
                        </div>
                    </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="location" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select">
                            <option value="Upcoming">Upcoming</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="eventFormSubmit">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    const eventForm  = document.getElementById('eventForm');

    document.getElementById('btnOpenCreateEvent').addEventListener('click', () => {
        eventForm.reset();
        eventForm.action = '{{ route("events.store") }}';
        document.getElementById('eventFormMethod').value = '';
        document.getElementById('eventModalLabel').innerText = "Create Event";
        document.getElementById('eventFormSubmit').innerText = "Save Event";
        eventModal.show();
    });

    document.querySelectorAll('.btn-edit-event').forEach(btn => {
        btn.addEventListener('click', () => {
            const d = btn.dataset;
            eventForm.action = `/events/${d.event_id}`;
            document.getElementById('eventFormMethod').value = 'PUT';
            document.getElementById('event_name').value    = d.event_name;
            document.getElementById('event_type').value    = d.event_type;
            document.getElementById('event_date').value    = d.event_date;
            document.getElementById('time_started').value  = d.time_started ?? '';
            document.getElementById('time_ended').value    = d.time_ended   ?? '';
            document.getElementById('location').value      = d.location;
            document.getElementById('description').value   = d.description;
            document.getElementById('status').value        = d.status;
            document.getElementById('eventModalLabel').innerText  = "Edit Event";
            document.getElementById('eventFormSubmit').innerText  = "Update Event";
            eventModal.show();
        });
    });

    function confirmDeleteEvent(id) {
        Swal.fire({
            title:'Delete Event?', text:"This will remove the event and its records!",
            icon:'warning', showCancelButton:true,
            confirmButtonColor:'#d33', confirmButtonText:'Yes, delete!'
        }).then(r => { if (r.isConfirmed) document.getElementById('delete-form-'+id).submit(); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success')) Swal.fire({ title:'Success!', text:'{{ session("success") }}', icon:'success', confirmButtonColor:'#1e3a8a' }); @endif
        @if(session('error'))   Swal.fire({ title:'Error!',   text:'{{ session("error") }}',   icon:'error',   confirmButtonColor:'#d33'    }); @endif
        @if($errors->any())     Swal.fire({ title:'Validation Error!', html:@json($errors->all()).join('<br>'), icon:'error', confirmButtonColor:'#d33' }); @endif
    });
</script>
@endpush
@endsection