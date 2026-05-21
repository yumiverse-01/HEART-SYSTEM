@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3><i class="fas fa-file-medical me-2"></i>Health Services</h3>
    <button class="btn btn-primary px-4" onclick="openCreateModal()">
        <i class="fas fa-plus me-2"></i> Document Service
    </button>
</div>

{{-- Search --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('service-records.index') }}">
            <div class="row g-2">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Beneficiary name or service type..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i></button>
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
                    <th class="ps-3">Service Details</th>
                    <th>Beneficiary</th>
                    <th>Event</th>
                    <th>Diagnosis & Treatment</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-primary">{{ $record->service_type ?? 'General Checkup' }}</div>
                            <small class="text-muted">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($record->service_date)->format('M d, Y') }}
                            </small>
                        </td>
                        <td class="fw-bold">{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $record->event->event_name ?? 'N/A' }}</span></td>
                        <td style="max-width:280px;">
                            <div class="text-truncate small"><strong>Dx:</strong> {{ $record->diagnosis ?? 'None' }}</div>
                            <div class="text-truncate small text-muted"><strong>Rx:</strong> {{ $record->treatment_given ?? 'None' }}</div>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" onclick="openEditModal({{ json_encode($record) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $record->service_id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">No health services documented yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile card list --}}
<div class="d-md-none">
    @forelse($records as $record)
    <div class="table-card mb-2 p-3">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <div style="min-width:0;">
                <div class="fw-bold text-primary text-truncate">{{ $record->service_type ?? 'General Checkup' }}</div>
                <small class="text-muted d-block">
                    <i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($record->service_date)->format('M d, Y') }}
                </small>
                <small class="d-block mt-1 fw-bold">{{ $record->beneficiary->first_name }} {{ $record->beneficiary->last_name }}</small>
                <span class="badge bg-light text-dark border mt-1">{{ $record->event->event_name ?? 'N/A' }}</span>
                <div class="mt-1 small text-truncate"><strong>Dx:</strong> {{ $record->diagnosis ?? 'None' }}</div>
                <div class="small text-muted text-truncate"><strong>Rx:</strong> {{ $record->treatment_given ?? 'None' }}</div>
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
                <button class="btn btn-sm btn-outline-primary" onclick="openEditModal({{ json_encode($record) }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $record->service_id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
        <p class="text-center text-muted py-4">No health services documented yet.</p>
    @endforelse
</div>

{{-- Pagination --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
    <small class="text-secondary">
        Showing {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }}
    </small>
    <div class="pagination-custom d-flex gap-1 flex-wrap">
        @if($records->onFirstPage())
            <span class="btn btn-sm btn-light disabled border">Prev</span>
        @else
            <a href="{{ $records->appends(request()->query())->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">Prev</a>
        @endif
        @foreach($records->getUrlRange(max(1,$records->currentPage()-2),min($records->lastPage(),$records->currentPage()+2)) as $page => $url)
            <a href="{{ $records->appends(request()->query())->url($page) }}"
               class="btn btn-sm {{ $page==$records->currentPage()?'btn-primary active':'btn-outline-primary' }} px-3">{{ $page }}</a>
        @endforeach
        @if($records->hasMorePages())
            <a href="{{ $records->appends(request()->query())->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">Next</a>
        @else
            <span class="btn btn-sm btn-light disabled border">Next</span>
        @endif
    </div>
</div>

@foreach($records as $record)
    <form id="delete-form-{{ $record->service_id }}"
          action="{{ route('service-records.destroy', $record->service_id) }}"
          method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach

{{-- Modal --}}
<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle" style="color:#1e3a8a;">New Service Documentation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="serviceForm" method="POST">
                @csrf
                <div id="methodContainer"></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Event <span class="text-danger">*</span></label>
                            <select name="event_id" id="event_id" class="form-select" required>
                                <option value="">-- Select Event --</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Beneficiary <span class="text-danger">*</span></label>
                            <select name="beneficiary_id" id="beneficiary_id" class="form-select" required>
                                <option value="">-- Search Beneficiary --</option>
                                @foreach($beneficiaries as $b)
                                    <option value="{{ $b->beneficiary_id }}">{{ $b->first_name }} {{ $b->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Service Type <span class="text-danger">*</span></label>
                            <input type="text" name="service_type" id="service_type" class="form-control" required placeholder="e.g. Blood Pressure Check">
                        </div>
                        <div class="col-12 col-sm-6">
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
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">Save Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
    const serviceForm  = document.getElementById('serviceForm');

    $(document).ready(function() {
        $('#beneficiary_id').select2({
            theme: "bootstrap-5",
            dropdownParent: $('#serviceModal'),
            placeholder: "-- Search Beneficiary --",
            allowClear: true,
            width: '100%'
        });
    });

    function openCreateModal() {
        serviceForm.reset();
        serviceForm.action = "{{ route('service-records.store') }}";
        document.getElementById('methodContainer').innerHTML = '';
        $('#beneficiary_id').val(null).trigger('change');
        document.getElementById('modalTitle').innerText = "New Service Documentation";
        document.getElementById('submitBtn').innerText  = "Save Document";
        serviceModal.show();
    }

    function openEditModal(record) {
        serviceForm.reset();
        serviceForm.action = `/service-records/${record.service_id}`;
        document.getElementById('methodContainer').innerHTML = '@method("PUT")';
        document.getElementById('modalTitle').innerText = "Edit Service Documentation";
        document.getElementById('submitBtn').innerText  = "Update Document";
        document.getElementById('event_id').value    = record.event_id;
        document.getElementById('service_type').value = record.service_type;
        $('#beneficiary_id').val(record.beneficiary_id).trigger('change');
        if (record.service_date) document.getElementById('service_date').value = record.service_date.substring(0,10);
        document.getElementById('diagnosis').value      = record.diagnosis;
        document.getElementById('treatment_given').value = record.treatment_given;
        serviceModal.show();
    }

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

    function confirmDelete(id) {
        Swal.fire({
            title:'Delete Record?', text:"This action cannot be undone.",
            icon:'warning', showCancelButton:true,
            confirmButtonColor:'#d33', confirmButtonText:'Yes, delete it!',
            cancelButtonColor: '#6c757d'
        }).then(r => { if (r.isConfirmed) document.getElementById('delete-form-'+id).submit(); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success')) showToast('success', '{{ session("success") }}'); @endif
        @if(session('error'))   showToast('error', '{{ session("error") }}'); @endif
        @if($errors->any())     showToast('error', @json($errors->all()).join(' ')); @endif
    });
</script>
@endpush
@endsection