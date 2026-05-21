@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3><i class="fas fa-user-shield me-2"></i>User Management</h3>
    <button class="btn btn-primary px-4" onclick="openCreateModal()">
        <i class="fas fa-plus me-2"></i> Add User
    </button>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('user-management.index') }}">
            <div class="row g-2">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Name, username or email..." value="{{ request('search') }}">
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
                    <th class="ps-3">Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="ps-3">
                        <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                        <small class="text-muted">{{ $user->email }}</small>
                    </td>
                    <td><small>{{ $user->username }}</small></td>
                    <td><span class="badge bg-secondary">{{ $user->role?->name ?? 'No Role' }}</span></td>
                    <td>
                        <span class="badge {{ $user->status==='active'?'bg-success':'bg-danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td><small class="text-muted">{{ $user->last_login ? $user->last_login->format('M d, Y h:i A') : 'Never' }}</small></td>
                    <td class="text-end pe-3">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditModal({{ json_encode($user) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if($user->user_id !== auth()->id())
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $user->user_id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile list --}}
<div class="d-md-none">
    @forelse($users as $user)
    <div class="table-card mb-2 p-3">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <div style="min-width:0;">
                <div class="fw-bold text-truncate">{{ $user->first_name }} {{ $user->last_name }}</div>
                <small class="text-muted d-block text-truncate">{{ $user->email }}</small>
                <small class="text-muted">@{{ $user->username }}</small>
                <div class="mt-1">
                    <span class="badge bg-secondary me-1">{{ $user->role?->name ?? 'No Role' }}</span>
                    <span class="badge {{ $user->status==='active'?'bg-success':'bg-danger' }}">{{ ucfirst($user->status) }}</span>
                </div>
                <small class="text-muted d-block mt-1">
                    Last login: {{ $user->last_login ? $user->last_login->format('M d, Y') : 'Never' }}
                </small>
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
                <button class="btn btn-sm btn-outline-primary" onclick="openEditModal({{ json_encode($user) }})">
                    <i class="fas fa-edit"></i>
                </button>
                @if($user->user_id !== auth()->id())
                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $user->user_id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
    @empty
        <p class="text-center text-muted py-4">No users found.</p>
    @endforelse
</div>

{{-- Pagination --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
    <small class="text-secondary">
        Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
    </small>
    <div class="pagination-custom d-flex gap-1 flex-wrap">
        @if($users->onFirstPage())
            <span class="btn btn-sm btn-light disabled border">Prev</span>
        @else
            <a href="{{ $users->appends(request()->query())->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">Prev</a>
        @endif
        @foreach($users->getUrlRange(max(1,$users->currentPage()-2),min($users->lastPage(),$users->currentPage()+2)) as $page => $url)
            <a href="{{ $users->appends(request()->query())->url($page) }}"
               class="btn btn-sm {{ $page==$users->currentPage()?'btn-primary active':'btn-outline-primary' }} px-3">{{ $page }}</a>
        @endforeach
        @if($users->hasMorePages())
            <a href="{{ $users->appends(request()->query())->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">Next</a>
        @else
            <span class="btn btn-sm btn-light disabled border">Next</span>
        @endif
    </div>
</div>

@foreach($users as $user)
    @if($user->user_id !== auth()->id())
        <form id="delete-form-{{ $user->user_id }}"
              action="{{ route('user-management.destroy', $user->user_id) }}"
              method="POST" class="d-none">
            @csrf @method('DELETE')
        </form>
    @endif
@endforeach

{{-- Modal --}}
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm" method="POST">
                @csrf
                <div id="methodContainer"></div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">
                                Password
                                <span class="text-danger" id="passwordRequired">*</span>
                                <small class="text-muted" id="passwordHint" style="display:none;">(blank = keep current)</small>
                            </label>
                            <input type="password" name="password" id="password" class="form-control">
                            <div id="passwordLengthError" class="text-danger small mt-1" style="display:none;">Min 8 characters</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Confirm Password <span class="text-danger" id="confirmRequired">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            <div id="passwordError" class="text-danger small mt-1" style="display:none;">Passwords do not match!</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role_id" id="role_id" class="form-select" required>
                                <option value="">-- Select --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    const userForm  = document.getElementById('userForm');
    const passwordInput = document.getElementById('password');
    const confirmInput  = document.getElementById('password_confirmation');
    const submitBtn     = document.getElementById('submitBtn');

    function validatePasswords() {
        const p = passwordInput.value, c = confirmInput.value;
        let err = false;
        if (passwordInput.required && p.length > 0 && p.length < 8) {
            document.getElementById('passwordLengthError').style.display = 'block';
            err = true;
        } else { document.getElementById('passwordLengthError').style.display = 'none'; }
        if (c.length > 0 && p !== c) {
            document.getElementById('passwordError').style.display = 'block';
            err = true;
        } else { document.getElementById('passwordError').style.display = 'none'; }
        submitBtn.disabled = err;
    }
    passwordInput.addEventListener('input', validatePasswords);
    confirmInput.addEventListener('input', validatePasswords);

    function openCreateModal() {
        userForm.reset();
        userForm.action = "{{ route('user-management.store') }}";
        document.getElementById('methodContainer').innerHTML = '';
        document.getElementById('modalTitle').innerText = 'Add User';
        document.getElementById('submitBtn').innerText  = 'Save User';
        document.getElementById('passwordRequired').style.display = 'inline';
        document.getElementById('passwordHint').style.display     = 'none';
        document.getElementById('confirmRequired').style.display  = 'inline';
        passwordInput.required = true;
        document.getElementById('password_confirmation').required = true;
        submitBtn.disabled = false;
        userModal.show();
    }

    function openEditModal(user) {
        userForm.reset();
        userForm.action = `/user-management/${user.user_id}`;
        document.getElementById('methodContainer').innerHTML = '@method("PUT")';
        document.getElementById('modalTitle').innerText = 'Edit User';
        document.getElementById('submitBtn').innerText  = 'Update User';
        document.getElementById('passwordRequired').style.display = 'none';
        document.getElementById('passwordHint').style.display     = 'inline';
        document.getElementById('confirmRequired').style.display  = 'none';
        passwordInput.required = false;
        document.getElementById('password_confirmation').required = false;
        document.getElementById('first_name').value = user.first_name;
        document.getElementById('last_name').value  = user.last_name;
        document.getElementById('username').value   = user.username;
        document.getElementById('email').value      = user.email;
        document.getElementById('role_id').value    = user.role_id;
        document.getElementById('status').value     = user.status;
        submitBtn.disabled = false;
        userModal.show();
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
        Swal.fire({ title:'Delete User?', text:"This cannot be undone.", icon:'warning',
            showCancelButton:true, confirmButtonColor:'#d33', confirmButtonText:'Yes, delete!',
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