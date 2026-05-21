@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3><i class="fas fa-users me-2"></i>Beneficiaries</h3>
    @can('create-beneficiaries')
        <button class="btn btn-primary px-4" id="btnOpenCreateBeneficiary">
            <i class="fas fa-plus me-2"></i> Add Beneficiary
        </button>
    @endcan
</div>

{{-- Search / Filter --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form action="{{ route('beneficiaries.index') }}" method="GET">
            <div class="row g-2">
                <div class="col-12 col-sm-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Name, email or contact..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <select name="sex" class="form-select">
                        <option value="">All Genders</option>
                        <option value="Male"   {{ request('sex')=='Male'   ? 'selected':'' }}>Male</option>
                        <option value="Female" {{ request('sex')=='Female' ? 'selected':'' }}>Female</option>
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

{{-- Table — stacks to cards on mobile --}}
<div class="table-card">
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-secondary small text-uppercase">
                    <th class="ps-3">Full Name</th>
                    <th>Profile</th>
                    <th>Contact</th>
                    <th>Registered</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beneficiaries as $b)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:36px;height:36px;font-size:0.8rem;font-weight:700;">
                                    {{ substr($b->first_name,0,1) }}{{ substr($b->last_name,0,1) }}
                                </div>
                                <div style="min-width:0;">
                                    <div class="fw-bold text-primary text-truncate">
                                        {{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}
                                    </div>
                                    <small class="text-muted text-truncate d-block">{{ $b->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $b->sex ?? 'N/A' }}</span>
                            <small class="d-block text-muted mt-1">{{ $b->age ?? '?' }} yrs</small>
                        </td>
                        <td>
                            <small class="d-block"><i class="fas fa-phone text-muted me-1"></i>{{ $b->contact_number ?? '-' }}</small>
                            <small class="text-muted">{{ $b->guardian_name ?? 'No Guardian' }}</small>
                        </td>
                        <td>
                            <small class="fw-medium">{{ $b->date_registered ? \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') : '-' }}</small>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary btn-edit-beneficiary"
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
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDeleteBeneficiary({{ $b->beneficiary_id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">No beneficiaries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile card list --}}
    <div class="d-md-none">
        @forelse($beneficiaries as $b)
        <div class="p-3 border-bottom">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div style="min-width:0;">
                    <div class="fw-bold text-primary text-truncate">
                        {{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}
                    </div>
                    <small class="text-muted d-block text-truncate">
                        <i class="far fa-envelope me-1"></i>{{ $b->email }}
                    </small>
                    <small class="text-muted">
                        <i class="fas fa-phone me-1"></i>{{ $b->contact_number ?? '-' }}
                        &nbsp;·&nbsp;
                        {{ $b->sex ?? 'N/A' }}, {{ $b->age ?? '?' }} yrs
                    </small>
                    <small class="text-muted d-block">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $b->date_registered ? \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') : '-' }}
                    </small>
                </div>
                <div class="d-flex gap-1 flex-shrink-0">
                    <button class="btn btn-sm btn-outline-primary btn-edit-beneficiary"
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
                    <button class="btn btn-sm btn-outline-danger"
                            onclick="confirmDeleteBeneficiary({{ $b->beneficiary_id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
            <p class="text-center text-muted py-4">No beneficiaries found.</p>
        @endforelse
    </div>
</div>

{{-- Pagination --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
    <small class="text-secondary">
        Showing {{ $beneficiaries->firstItem() ?? 0 }}–{{ $beneficiaries->lastItem() ?? 0 }}
        of {{ $beneficiaries->total() }}
    </small>
    <div class="pagination-custom d-flex gap-1 flex-wrap">
        @if($beneficiaries->onFirstPage())
            <span class="btn btn-sm btn-light disabled border">Prev</span>
        @else
            <a href="{{ $beneficiaries->appends(request()->query())->previousPageUrl() }}"
               class="btn btn-sm btn-outline-primary">Prev</a>
        @endif
        @foreach($beneficiaries->getUrlRange(max(1,$beneficiaries->currentPage()-1), min($beneficiaries->lastPage(),$beneficiaries->currentPage()+1)) as $page => $url)
            <a href="{{ $beneficiaries->appends(request()->query())->url($page) }}"
               class="btn btn-sm {{ $page == $beneficiaries->currentPage() ? 'btn-primary active' : 'btn-outline-primary' }} px-3">
               {{ $page }}
            </a>
        @endforeach
        @if($beneficiaries->hasMorePages())
            <a href="{{ $beneficiaries->appends(request()->query())->nextPageUrl() }}"
               class="btn btn-sm btn-outline-primary">Next</a>
        @else
            <span class="btn btn-sm btn-light disabled border">Next</span>
        @endif
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="beneficiaryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="beneficiaryForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="beneficiaryFormMethod">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="beneficiaryModalLabel">Add Beneficiary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12 col-sm-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control">
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Birth Date <span class="text-danger">*</span></label>
                            <input type="date" name="birth_date" id="birth_date" class="form-control" required>
                        </div>
                        <div class="col-6 col-sm-4">
                            <label class="form-label">Age <span class="text-danger">*</span></label>
                            <input type="number" name="age" id="age" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label">Sex <span class="text-danger">*</span></label>
                            <select name="sex" id="sex" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" id="address" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" name="contact_number" id="contact_number" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Guardian Name</label>
                            <input type="text" name="guardian_name" id="guardian_name" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Date Registered <span class="text-danger">*</span></label>
                            <input type="date" name="date_registered" id="date_registered" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="beneficiaryFormSubmit">Save Beneficiary</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($beneficiaries as $b)
    <form id="delete-form-{{ $b->beneficiary_id }}"
          action="{{ route('beneficiaries.destroy', $b->beneficiary_id) }}"
          method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const bModal = new bootstrap.Modal(document.getElementById('beneficiaryModal'));
    const bForm  = document.getElementById('beneficiaryForm');

    document.getElementById('btnOpenCreateBeneficiary')?.addEventListener('click', () => {
        bForm.reset();
        bForm.action = '{{ route("beneficiaries.store") }}';
        document.getElementById('beneficiaryFormMethod').value = '';
        document.getElementById('beneficiaryModalLabel').innerText = "Add Beneficiary";
        document.getElementById('beneficiaryFormSubmit').innerText = "Save";
        bModal.show();
    });

    document.querySelectorAll('.btn-edit-beneficiary').forEach(btn => {
        btn.addEventListener('click', () => {
            const d = btn.dataset;
            bForm.action = `/beneficiaries/${d.beneficiary_id}`;
            document.getElementById('beneficiaryFormMethod').value = 'PUT';
            ['first_name','middle_name','last_name','email','birth_date','age','sex',
             'address','contact_number','guardian_name','date_registered'].forEach(k => {
                const el = document.getElementById(k);
                if (el) el.value = d[k] ?? '';
            });
            document.getElementById('beneficiaryModalLabel').innerText = "Edit Beneficiary";
            document.getElementById('beneficiaryFormSubmit').innerText = "Update";
            bModal.show();
        });
    });

    function confirmDeleteBeneficiary(id) {
        Swal.fire({
            title: 'Delete Beneficiary?',
            text: "All associated records will be removed.",
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete!',
            cancelButtonColor: '#6c757d'
        }).then(r => { if (r.isConfirmed) document.getElementById('delete-form-' + id).submit(); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            Swal.fire({ title:'Success!', text:'{{ session("success") }}', icon:'success', confirmButtonColor:'#1e3a8a' });
        @endif
        @if(session('error'))
            Swal.fire({ title:'Error!', text:'{{ session("error") }}', icon:'error', confirmButtonColor:'#d33' });
        @endif
        @if($errors->any())
            Swal.fire({ title:'Validation Error!', html:@json($errors->all()).join('<br>'), icon:'error', confirmButtonColor:'#d33' });
        @endif
    });
</script>
@endpush
@endsection