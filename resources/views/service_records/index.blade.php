@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0"><i class="fas fa-file-medical"></i> Health Services Documentation</h3>
    <button class="btn btn-primary px-4 shadow-sm" onclick="openCreateModal()">
        <i class="fas fa-plus me-2"></i> Document Service
    </button>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('service-records.index') }}" class="row g-2">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by beneficiary name or service type..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-navy w-100" style="background-color: #1e3a8a; color: white;">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle shadow-sm bg-white">
        <thead class="bg-light">
            <tr>
                <th>Service Details</th>
                <th>Beneficiary</th>
                <th>Event</th>
                <th>Diagnosis & Treatment</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>
                        <div class="fw-bold text-primary">{{ $record->service_type ?? 'General Checkup' }}</div>
                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($record->service_date)->format('M d, Y') }}</small>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $record->event->event_name ?? 'N/A' }}</span>
                    </td>
                    <td style="max-width: 300px;">
                        <div class="text-truncate"><strong>Dx:</strong> {{ $record->diagnosis ?? 'None' }}</div>
                        <small class="text-muted d-block text-truncate"><strong>Rx:</strong> {{ $record->treatment_given ?? 'None' }}</small>
                    </td>
                    <td class="text-end">
                        <div class="btn-group shadow-sm">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditModal({{ json_encode($record) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $record->service_id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <form id="delete-form-{{ $record->service_id }}" action="{{ route('service-records.destroy', $record->service_id) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">No health services documented yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-4 px-2">
    <div class="text-secondary small">
        Showing <strong>{{ $records->firstItem() ?? 0 }}</strong> to <strong>{{ $records->lastItem() ?? 0 }}</strong> of <strong>{{ $records->total() }}</strong> entries
    </div>
    <div class="pagination-custom d-flex gap-1">
        @if ($records->onFirstPage())
            <span class="btn btn-sm btn-light disabled border">Previous</span>
        @else
            <a href="{{ $records->appends(request()->query())->previousPageUrl() }}" class="btn btn-sm btn-outline-primary shadow-sm">Previous</a>
        @endif

        @foreach ($records->getUrlRange(max(1, $records->currentPage() - 2), min($records->lastPage(), $records->currentPage() + 2)) as $page => $url)
            <a href="{{ $records->appends(request()->query())->url($page) }}" class="btn btn-sm {{ $page == $records->currentPage() ? 'btn-primary active' : 'btn-outline-primary' }} px-3">{{ $page }}</a>
        @endforeach

        @if ($records->hasMorePages())
            <a href="{{ $records->appends(request()->query())->nextPageUrl() }}" class="btn btn-sm btn-outline-primary shadow-sm">Next</a>
        @else
            <span class="btn btn-sm btn-light disabled border">Next</span>
        @endif
    </div>
</div>

<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle" style="color: #1e3a8a;">New Service Documentation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="serviceForm" method="POST">
                @csrf
                <div id="methodContainer"></div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Event <span class="text-danger">*</span></label>
                            <select name="event_id" id="event_id" class="form-select" required>
                                <option value="">-- Select Event --</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Beneficiary <span class="text-danger">*</span></label>
                            <select name="beneficiary_id" id="beneficiary_id" class="form-select" required>
                                <option value="">-- Search Beneficiary --</option>
                                @foreach($beneficiaries as $b)
                                    <option value="{{ $b->beneficiary_id }}">
                                        {{ $b->first_name }} {{ $b->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service Type <span class="text-danger">*</span></label>
                            <input type="text" name="service_type" id="service_type" class="form-control" required placeholder="e.g. Blood Pressure Check">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service Date <span class="text-danger">*</span></label>
                            <input type="date" name="service_date" id="service_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Diagnosis/Findings <span class="text-danger">*</span></label>
                            <textarea name="diagnosis" id="diagnosis" class="form-control" rows="2" required placeholder="Describe findings..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Treatment Given & Remarks</label>
                            <textarea name="treatment_given" id="treatment_given" class="form-control" rows="2" placeholder="Prescriptions or actions taken..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Save Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Define the modal object globally
    const serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
    const serviceForm = document.getElementById('serviceForm');
    const methodContainer = document.getElementById('methodContainer');

    // Initialize Select2 when page loads
    $(document).ready(function() {
        $('#beneficiary_id').select2({
            theme: "bootstrap-5",
            dropdownParent: $('#serviceModal'), // Essential for search box to be clickable in Bootstrap modals
            placeholder: "-- Search Beneficiary --",
            allowClear: true,
            width: '100%'
        });
    });

    function openCreateModal() {
        serviceForm.reset();
        serviceForm.action = "{{ route('service-records.store') }}";
        methodContainer.innerHTML = '';
        
        // Clear Select2
        $('#beneficiary_id').val(null).trigger('change');
        
        document.getElementById('modalTitle').innerText = "New Service Documentation";
        document.getElementById('submitBtn').innerText = "Save Document";
        serviceModal.show();
    }

    function openEditModal(record) {
        serviceForm.reset();
        serviceForm.action = `/service-records/${record.service_id}`;
        methodContainer.innerHTML = '@method("PUT")';
        document.getElementById('modalTitle').innerText = "Edit Service Documentation";
        document.getElementById('submitBtn').innerText = "Update Document";

        // Fill standard fields
        document.getElementById('event_id').value = record.event_id;
        document.getElementById('service_type').value = record.service_type;
        
        // Fill and Trigger Select2
        $('#beneficiary_id').val(record.beneficiary_id).trigger('change');

        // Autofill Date correctly
        if (record.service_date) {
            document.getElementById('service_date').value = record.service_date.substring(0, 10);
        }

        document.getElementById('diagnosis').value = record.diagnosis;
        document.getElementById('treatment_given').value = record.treatment_given;
        
        serviceModal.show();
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete Record?',
            text: "This action cannot be undone.",
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
@endsection