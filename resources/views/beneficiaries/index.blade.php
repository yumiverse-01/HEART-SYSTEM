@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-users"></i> Beneficiaries</h3>
    <button class="btn btn-primary" id="btnOpenCreateBeneficiary">
        <i class="fas fa-user-plus"></i> Add Beneficiary
    </button>
</div>

<div class="row g-4">
    @forelse($beneficiaries as $b)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}</h5>
                    <p class="card-text mb-1">
                        <i class="fas fa-birthday-cake me-1"></i>
                        {{ $b->age ?? 'N/A' }}
                        @if($b->sex)
                            &middot; <i class="fas fa-{{ strtolower($b->sex)=='male'?'mars':'venus' }}"></i> {{ $b->sex }}
                        @endif
                    </p>
                    <p class="card-text mb-1"><i class="fas fa-phone me-1"></i> {{ $b->contact_number ?? '-' }}</p>
                    <p class="card-text mb-3"><i class="fas fa-user-friends me-1"></i> {{ $b->guardian_name ?? '-' }}</p>
                    <div class="mt-auto">
                        <button type="button" 
                                class="btn btn-sm btn-outline-primary me-2 btn-edit-beneficiary"
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
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        
                        <form action="{{ route('beneficiaries.destroy',$b->beneficiary_id) }}" method="POST" class="d-inline delete-beneficiary-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-beneficiary">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted mb-0">No beneficiaries found</p>
        </div>
    @endforelse
</div>

<div class="mt-3">
    {{ $beneficiaries->links() }}
</div>

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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" id="middle_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Age</label>
                                <input type="number" name="age" id="age" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Sex</label>
                                <select name="sex" id="sex" class="form-select">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control">
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
                        <label class="form-label">Date Registered</label>
                        <input type="date" name="date_registered" id="date_registered" class="form-control">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const beneficiaryModalEl = document.getElementById('beneficiaryModal');
    const beneficiaryModal = new bootstrap.Modal(beneficiaryModalEl);

    // Unified Modal Function
    function openBeneficiaryModal(mode, data = {}) {
        const form = document.getElementById('beneficiaryForm');
        const methodInput = document.getElementById('beneficiaryFormMethod');
        const titleEl = document.getElementById('beneficiaryModalLabel');
        const submitBtn = document.getElementById('beneficiaryFormSubmit');

        if (mode === 'create') {
            titleEl.textContent = 'Add Beneficiary';
            form.action = '{{ route('beneficiaries.store') }}';
            methodInput.value = '';
            submitBtn.textContent = 'Save';
            form.reset();
        } else if (mode === 'edit') {
            titleEl.textContent = 'Edit Beneficiary';
            form.action = '/beneficiaries/' + data.beneficiary_id;
            methodInput.value = 'PUT';
            submitBtn.textContent = 'Update';
            
            Object.keys(data).forEach(key => {
                const field = document.getElementById(key);
                if (field) { field.value = data[key]; }
            });
        }
        beneficiaryModal.show();
    }

    // Event Listeners
    document.getElementById('btnOpenCreateBeneficiary').addEventListener('click', () => {
        openBeneficiaryModal('create');
    });

    document.querySelectorAll('.btn-edit-beneficiary').forEach(btn => {
        btn.addEventListener('click', () => {
            openBeneficiaryModal('edit', btn.dataset);
        });
    });

    // SweetAlert2 Delete Confirmation
    document.querySelectorAll('.btn-delete-beneficiary').forEach(btn => {
        btn.addEventListener('click', function() {
            const deleteForm = this.closest('.delete-beneficiary-form');
            
            Swal.fire({
                title: 'Delete Beneficiary?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteForm.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection