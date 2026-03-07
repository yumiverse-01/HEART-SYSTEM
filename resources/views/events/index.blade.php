@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-calendar-alt"></i> Outreach Events</h3>
    <button class="btn btn-primary" id="btnOpenCreateEvent">
        <i class="fas fa-plus"></i> Create Event
    </button>
</div>

<div class="row g-4">
    @forelse($events as $event)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $event->event_name }}</h5>
                    <p class="card-text mb-1"><i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</p>
                    <p class="card-text mb-1"><i class="fas fa-map-marker-alt me-1"></i> {{ $event->location ?? '—' }}</p>
                    <div class="mb-2">
                        @if($event->status === 'Upcoming')
                            <span class="badge bg-warning text-dark">{{ $event->status }}</span>
                        @elseif($event->status === 'Completed')
                            <span class="badge bg-success">{{ $event->status }}</span>
                        @else
                            <span class="badge bg-danger">{{ $event->status }}</span>
                        @endif
                    </div>
                    <div class="mt-auto">
                        <button type="button" 
                                class="btn btn-sm btn-outline-primary me-2 btn-edit-event"
                                data-event_id="{{ $event->event_id }}"
                                data-event_name="{{ $event->event_name }}"
                                data-event_type="{{ $event->event_type }}"
                                data-event_date="{{ $event->event_date }}"
                                data-location="{{ $event->location }}"
                                data-description="{{ $event->description }}"
                                data-status="{{ $event->status }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form action="{{ route('events.destroy',$event->event_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this event?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted mb-0">No events found</p>
        </div>
    @endforelse
</div>

<!-- event modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="eventForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="eventFormMethod" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Create Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Event Name</label>
                        <input type="text" name="event_name" id="event_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Type</label>
                        <input type="text" name="event_type" id="event_type" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="event_date" id="event_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
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
    const eventModalEl = document.getElementById('eventModal');
    const eventModal = new bootstrap.Modal(eventModalEl);

    function openEventModal(mode, data = {}) {
        const form = document.getElementById('eventForm');
        const methodInput = document.getElementById('eventFormMethod');
        const titleEl = document.getElementById('eventModalLabel');
        const submitBtn = document.getElementById('eventFormSubmit');

        if (mode === 'create') {
            titleEl.textContent = 'Create Event';
            form.action = '{{ route('events.store') }}';
            methodInput.value = '';
            submitBtn.textContent = 'Save Event';
            form.reset();
        } else if (mode === 'edit') {
            titleEl.textContent = 'Edit Event';
            form.action = '/events/' + data.event_id;
            methodInput.value = 'PUT';
            submitBtn.textContent = 'Update Event';
            // populate fields
            document.getElementById('event_name').value = data.event_name || '';
            document.getElementById('event_type').value = data.event_type || '';
            document.getElementById('event_date').value = data.event_date || '';
            document.getElementById('location').value = data.location || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('status').value = data.status || 'Upcoming';
        }
        eventModal.show();
    }

    document.getElementById('btnOpenCreateEvent').addEventListener('click', () => {
        openEventModal('create');
    });

    document.querySelectorAll('.btn-edit-event').forEach(btn => {
        btn.addEventListener('click', () => {
            const data = btn.dataset;
            openEventModal('edit', data);
        });
    });
</script>
@endpush

@endsection