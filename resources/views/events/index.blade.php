@extends('layouts.app')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <h3 class="fw-bold m-0"><i class="fas fa-calendar-alt "></i> Outreach Events</h3>
    <button class="btn btn-primary px-4 shadow-sm" id="btnOpenCreateEvent">
        <i class="fas fa-plus me-2"></i> Create Event
    </button>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('events.index') }}" method="GET" class="row g-2">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search event name or location..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="Upcoming" {{ request('status') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-navy w-100 shadow-sm" style="background-color: #1e3a8a; color: white;">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle shadow-sm bg-white">
        <thead class="bg-light">
            <tr class="text-secondary">
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
                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : '-' }}</small>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-light text-dark border px-3">{{ $event->event_type ?? 'General' }}</span>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 200px;"><i class="fas fa-map-marker-alt text-muted me-1"></i> {{ $event->location ?? '-' }}</div>
                    </td>
                    <td>
                        @php
                            $badgeClass = match($event->status) {
                                'Upcoming' => 'bg-warning text-dark',
                                'Completed' => 'bg-success text-white',
                                'Cancelled' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} shadow-sm" style="min-width: 85px;">{{ $event->status }}</span>
                    </td>
                    <td class="text-end pe-3">
                        <div class="btn-group shadow-sm">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-event" data-event_id="{{ $event->event_id }}" data-event_name="{{ $event->event_name }}" data-event_type="{{ $event->event_type }}" data-event_date="{{ $event->event_date }}" data-location="{{ $event->location }}" data-description="{{ $event->description }}" data-status="{{ $event->status }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteEvent({{ $event->event_id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <form id="delete-form-{{ $event->event_id }}" action="{{ route('events.destroy', $event->event_id) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">No outreach events found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-4 px-2">
    <div class="text-secondary small">
        Showing <strong>{{ $events->firstItem() ?? 0 }}</strong> to <strong>{{ $events->lastItem() ?? 0 }}</strong> of <strong>{{ $events->total() }}</strong> entries
    </div>
    <div class="pagination-custom d-flex gap-1">
        @if ($events->onFirstPage())
            <span class="btn btn-sm btn-light disabled border">Previous</span>
        @else
            <a href="{{ $events->appends(request()->query())->previousPageUrl() }}" class="btn btn-sm btn-outline-primary shadow-sm">Previous</a>
        @endif
        @foreach ($events->getUrlRange(max(1, $events->currentPage() - 2), min($events->lastPage(), $events->currentPage() + 2)) as $page => $url)
            <a href="{{ $events->appends(request()->query())->url($page) }}" class="btn btn-sm {{ $page == $events->currentPage() ? 'btn-primary active' : 'btn-outline-primary' }} px-3">{{ $page }}</a>
        @endforeach
        @if ($events->hasMorePages())
            <a href="{{ $events->appends(request()->query())->nextPageUrl() }}" class="btn btn-sm btn-outline-primary shadow-sm">Next</a>
        @else
            <span class="btn btn-sm btn-light disabled border">Next</span>
        @endif
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="eventModalLabel" style="color: #1e3a8a;">Create Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eventForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="eventFormMethod" value="">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Event Name <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" id="event_name" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <input type="text" name="event_type" id="event_type" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="event_date" id="event_date" class="form-control" required>
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
                    <button type="submit" class="btn btn-primary" id="eventFormSubmit">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    const eventForm = document.getElementById('eventForm');

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
            const data = btn.dataset;
            eventForm.action = `/events/${data.event_id}`;
            document.getElementById('eventFormMethod').value = 'PUT';
            document.getElementById('event_name').value = data.event_name;
            document.getElementById('event_type').value = data.event_type;
            document.getElementById('event_date').value = data.event_date;
            document.getElementById('location').value = data.location;
            document.getElementById('description').value = data.description;
            document.getElementById('status').value = data.status;
            document.getElementById('eventModalLabel').innerText = "Edit Event";
            document.getElementById('eventFormSubmit').innerText = "Update Event";
            eventModal.show();
        });
    });

    function confirmDeleteEvent(id) {
        Swal.fire({
            title: 'Delete Event?',
            text: "This will remove the event and its associated records!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
        });
    }
</script>
@endpush

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Check for session messages on page load
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonColor: '#1e3a8a'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session("error") }}',
                icon: 'error',
                confirmButtonColor: '#d33'
            });
        @endif

        @if($errors->any())
            let errorMessages = @json($errors->all());
            Swal.fire({
                title: 'Validation Error!',
                html: errorMessages.join('<br>'),
                icon: 'error',
                confirmButtonColor: '#d33'
            });
        @endif
    });
</script>
@endsection