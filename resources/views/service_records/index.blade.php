@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-12 p-4 bg-light min-vh-100">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold m-0">Health Services Documentation</h2>
                    <p class="text-muted">Document health service provided during outreach events</p>
                </div>
                <button class="btn btn-primary px-4 py-2 fw-bold" onclick="openCreateModal()">
                    <i class="fas fa-plus me-2"></i> Document Service
                </button>
            </div>

            <div class="service-records-feed">
                @forelse($records as $record)
                    <div class="card border-0 shadow-sm mb-3 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                                    <i class="fas fa-heartbeat fa-lg"></i>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="fw-bold mb-0">{{ $record->service_type ?? 'General Checkup' }}</h5>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)" 
                                                       onclick="openEditModal({{ json_encode($record) }})">
                                                       <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form id="delete-form-{{ $record->service_id }}" 
                                                          action="{{ route('service-records.destroy', $record->service_id) }}" 
                                                          method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="dropdown-item text-danger" 
                                                                onclick="confirmDelete({{ $record->service_id }})">
                                                            <i class="fas fa-trash me-2"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3">{{ $record->event->event_name ?? 'Outreach Event' }}</p>

                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-user-friends me-2 w-20px"></i>
                                            <span>{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}</span>
                                        </div>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="far fa-calendar me-2 w-20px"></i>
                                            <span>{{ \Carbon\Carbon::parse($record->service_date)->format('F d, Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-3 bg-light p-3 rounded-3">
                                        <p class="mb-1 fw-semibold text-dark">
                                            {{ $record->diagnosis ?? 'No diagnosis recorded' }}
                                        </p>
                                        @if($record->treatment_given)
                                            <small class="text-muted">{{ $record->treatment_given }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                        <p class="text-muted">No health services documented yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTitle">New Service Documentation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="serviceForm" method="POST">
                @csrf
                <div id="methodContainer"></div> <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Event</label>
                            <select name="event_id" id="event_id" class="form-select" required>
                                <option value="">-- Select Event --</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Beneficiary</label>
                            <select name="beneficiary_id" id="beneficiary_id" class="form-select" required>
                                <option value="">-- Select Beneficiary --</option>
                                @foreach($beneficiaries as $b)
                                    <option value="{{ $b->beneficiary_id }}">{{ $b->first_name }} {{ $b->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service Type</label>
                            <input type="text" name="service_type" id="service_type" class="form-control" placeholder="e.g. Blood Pressure Check">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service Date</label>
                            <input type="date" name="service_date" id="service_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Diagnosis/Findings</label>
                            <textarea name="diagnosis" id="diagnosis" class="form-control" rows="2" placeholder="e.g. 120/80 - Normal"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Treatment Given & Remarks</label>
                            <textarea name="treatment_given" id="treatment_given" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Save Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .w-20px { width: 20px; text-align: center; }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-2px); }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
    const serviceForm = document.getElementById('serviceForm');
    const methodContainer = document.getElementById('methodContainer');
    const modalTitle = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('submitBtn');

    // CREATE MODE
    function openCreateModal() {
        serviceForm.reset();
        serviceForm.action = "{{ route('service-records.store') }}";
        methodContainer.innerHTML = ''; // No PUT method
        modalTitle.innerText = "New Service Documentation";
        submitBtn.innerText = "Save Document";
        serviceModal.show();
    }

    // EDIT MODE
    function openEditModal(record) {
        serviceForm.reset();
        // Update Action URL
        serviceForm.action = `/service-records/${record.service_id}`;
        
        // Inject PUT method
        methodContainer.innerHTML = '@method("PUT")';
        
        modalTitle.innerText = "Edit Service Documentation";
        submitBtn.innerText = "Update Document";

        // Fill Fields
        document.getElementById('event_id').value = record.event_id;
        document.getElementById('beneficiary_id').value = record.beneficiary_id;
        document.getElementById('service_type').value = record.service_type;
        document.getElementById('service_date').value = record.service_date;
        document.getElementById('diagnosis').value = record.diagnosis;
        document.getElementById('treatment_given').value = record.treatment_given;

        serviceModal.show();
    }

    // DELETE CONFIRMATION
    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete Record?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush
@endsection