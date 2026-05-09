@extends('layouts.app')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <h3 class="fw-bold m-0"><i class="fas fa-users me-2"></i>Beneficiaries</h3>
    @can('create-beneficiaries')
        <button class="btn btn-primary px-4 shadow-sm" id="btnOpenCreateBeneficiary" style="background-color: #1e3a8a;">
            <i class="fas fa-plus me-2"></i> Add Beneficiary
        </button>
    @endcan
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('beneficiaries.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-12 col-md-7">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search name, email or contact..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select name="sex" class="form-select shadow-sm">
                        <option value="">All Genders</option>
                        <option value="Male" {{ request('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ request('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 text-md-end">
                    <button type="submit" class="btn btn-navy w-100 w-md-auto shadow-sm" style="background-color: #1e3a8a; color: white;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white mb-0">
            <thead class="bg-light">
                <tr class="text-secondary small text-uppercase">
                    <th class="ps-3" style="min-width: 250px;">Full Name</th>
                    <th style="min-width: 120px;">Profile</th>
                    <th style="min-width: 180px;">Contact Info</th>
                    <th>Registration</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beneficiaries as $b)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 38px; height: 38px; font-size: 0.85rem; font-weight: bold; flex-shrink: 0;">
                                    {{ substr($b->first_name, 0, 1) }}{{ substr($b->last_name, 0, 1) }}
                                </div>
                                <div class="text-truncate">
                                    <div class="fw-bold text-primary">{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}</div>
                                    <small class="text-muted"><i class="far fa-envelope me-1"></i> {{ $b->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">{{ $b->sex ?? 'N/A' }}</span>
                            <small class="d-block text-muted mt-1">{{ $b->age ?? '??' }} yrs old</small>
                        </td>
                        <td>
                            <div class="small mb-1"><i class="fas fa-phone text-muted me-2"></i>{{ $b->contact_number ?? '-' }}</div>
                            <div class="small text-muted text-truncate" style="max-width: 150px;">
                                <i class="fas fa-shield-alt me-2"></i>{{ $b->guardian_name ?? 'No Guardian' }}
                            </div>
                        </td>
                        <td class="small">
                            <span class="text-dark fw-medium">{{ $b->date_registered ? \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') : '-' }}</span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group shadow-sm">
                                <button type="button" class="btn btn-sm btn-light border btn-edit-beneficiary" 
                                    data-beneficiary_id="{{ $b->beneficiary_id }}" 
                                    data-first_name="{{ $b->first_name }}" 
                                    data-middle_name="{{ $b->middle_name }}" 
                                    data-last_name="{{ $b->last_name }}" 
                                    data-email="{{ $b->email }}" 
                                    data-birth_date="{{ $b->birth_date }}" 
                                    data-age="{{ $b->age }}" 
                                    data-sex="{{ $b->sex }}" 
                                    data-address="{{ $b->address }}" 
                                    data-contact_number="{{ $b->contact_number }}" 
                                    data-guardian_name="{{ $b->guardian_name }}" 
                                    data-date_registered="{{ $b->date_registered }}">
                                    <i class="fas fa-edit text-primary"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border text-danger" onclick="confirmDeleteBeneficiary({{ $b->beneficiary_id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $b->beneficiary_id }}" action="{{ route('beneficiaries.destroy', $b->beneficiary_id) }}" method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No beneficiaries found in the system.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 px-2 gap-3">
    <div class="text-secondary small">
        Showing <strong>{{ $beneficiaries->firstItem() ?? 0 }}</strong> to <strong>{{ $beneficiaries->lastItem() ?? 0 }}</strong> of <strong>{{ $beneficiaries->total() }}</strong> entries
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

{{-- BENEFICIARY MODAL (ADD/EDIT) --}}
<div class="modal fade" id="beneficiaryModal" tabindex="-1" aria-labelledby="beneficiaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="beneficiaryForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="beneficiaryFormMethod" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="beneficiaryModalLabel">Add Beneficiary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" id="middle_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" id="last_name" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Birth Date <span class="text-danger">*</span></label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Age <span class="text-danger">*</span></label>
                                <input type="number" name="age" id="age" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Sex <span class="text-danger">*</span></label>
                                <select name="sex" id="sex" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" name="address" id="address" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Guardian Name</label>
                                <input type="text" name="guardian_name" id="guardian_name" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Registered <span class="text-danger">*</span></label>
                        <input type="date" name="date_registered" id="date_registered" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="beneficiaryFormSubmit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const bModal = new bootstrap.Modal(document.getElementById('beneficiaryModal'));
    const bForm = document.getElementById('beneficiaryForm');

    document.getElementById('btnOpenCreateBeneficiary').addEventListener('click', () => {
        bForm.reset();
        bForm.action = '{{ route("beneficiaries.store") }}';
        document.getElementById('beneficiaryFormMethod').value = '';
        document.getElementById('beneficiaryModalLabel').innerText = "Add Beneficiary";
        document.getElementById('beneficiaryFormSubmit').innerText = "Save Beneficiary";
        bModal.show();
    });

    document.querySelectorAll('.btn-edit-beneficiary').forEach(btn => {
        btn.addEventListener('click', () => {
            const data = btn.dataset;
            bForm.action = `/beneficiaries/${data.beneficiary_id}`;
            document.getElementById('beneficiaryFormMethod').value = 'PUT';
            
            Object.keys(data).forEach(key => {
                const el = document.getElementById(key);
                if (el) el.value = data[key];
            });

            document.getElementById('beneficiaryModalLabel').innerText = "Edit Beneficiary";
            document.getElementById('beneficiaryFormSubmit').innerText = "Update Beneficiary";
            bModal.show();
        });
    });

    function confirmDeleteBeneficiary(id) {
        Swal.fire({
            title: 'Delete Beneficiary?',
            text: "All associated records for this person will be removed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonColor: '#6c757d'
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