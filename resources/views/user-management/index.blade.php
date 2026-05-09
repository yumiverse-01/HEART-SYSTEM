@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0"><i class="fas fa-user-shield"></i> User Management</h3>
    <button class="btn btn-primary px-4 shadow-sm" onclick="openCreateModal()">
        <i class="fas fa-plus me-2"></i> Add User
    </button>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('user-management.index') }}" class="row g-2">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0"
                        placeholder="Search by name, username or email..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn w-100" style="background-color: #1e3a8a; color: white;">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle shadow-sm bg-white">
        <thead class="bg-light">
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last Login</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ $user->role?->name ?? 'No Role' }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $user->last_login ? $user->last_login->format('M d, Y h:i A') : 'Never' }}
                        </small>
                    </td>
                    <td class="text-end">
                        <div class="btn-group shadow-sm">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditModal({{ json_encode($user) }})">
                                <i class="fas fa-edit"></i>
                            </button>

                            @if($user->user_id !== auth()->id())
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $user->user_id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>

                        @if($user->user_id !== auth()->id())
                            <form id="delete-form-{{ $user->user_id }}"
                                action="{{ route('user-management.destroy', $user->user_id) }}"
                                method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-4 px-2">
    <div class="text-secondary small">
        Showing <strong>{{ $users->firstItem() ?? 0 }}</strong> to
        <strong>{{ $users->lastItem() ?? 0 }}</strong> of
        <strong>{{ $users->total() }}</strong> entries
    </div>
    <div class="pagination-custom d-flex gap-1">
        @if ($users->onFirstPage())
            <span class="btn btn-sm btn-light disabled border">Previous</span>
        @else
            <a href="{{ $users->appends(request()->query())->previousPageUrl() }}" class="btn btn-sm btn-outline-primary shadow-sm">Previous</a>
        @endif

        @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
            <a href="{{ $users->appends(request()->query())->url($page) }}"
                class="btn btn-sm {{ $page == $users->currentPage() ? 'btn-primary active' : 'btn-outline-primary' }} px-3">
                {{ $page }}
            </a>
        @endforeach

        @if ($users->hasMorePages())
            <a href="{{ $users->appends(request()->query())->nextPageUrl() }}" class="btn btn-sm btn-outline-primary shadow-sm">Next</a>
        @else
            <span class="btn btn-sm btn-light disabled border">Next</span>
        @endif
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle" style="color: #1e3a8a;">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm" method="POST">
                @csrf
                <div id="methodContainer"></div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name"
                                class="form-control" required placeholder="Juan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name"
                                class="form-control" required placeholder="Dela Cruz">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username"
                                class="form-control" required placeholder="juandc">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email"
                                class="form-control" required placeholder="juan@email.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger" id="passwordRequired">*</span>
                                <small class="text-muted" id="passwordHint" style="display:none;">(leave blank to keep current)</small>
                            </label>
                            <input type="password" name="password" id="password" class="form-control">
                            <small class="text-muted" id="passwordLengthHint">Minimum 8 characters</small>
                            <div id="passwordLengthError" class="text-danger small mt-1" style="display: none;">
                                Password must be at least 8 characters long!
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password <span class="text-danger" id="confirmRequired">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            <div id="passwordError" class="text-danger small mt-1" style="display: none;">
                                Passwords do not match!
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role_id" id="role_id" class="form-select" required>
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
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
                    <button type="submit" class="btn btn-primary" id="submitBtn">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    const userForm  = document.getElementById('userForm');
    const methodContainer = document.getElementById('methodContainer');

    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');
    const passwordError = document.getElementById('passwordError');
    const passwordLengthError = document.getElementById('passwordLengthError');

    function validatePasswords() {
        const pass = passwordInput.value;
        const confirm = confirmInput.value;
        const isRequired = passwordInput.required;

        let hasError = false;

        // Check length if required
        if (isRequired && pass.length > 0 && pass.length < 8) {
            passwordLengthError.style.display = 'block';
            passwordInput.classList.add('is-invalid');
            hasError = true;
        } else {
            passwordLengthError.style.display = 'none';
            passwordInput.classList.remove('is-invalid');
        }

        // Check confirmation if user has started typing
        if (confirm.length > 0 && pass !== confirm) {
            passwordError.style.display = 'block';
            confirmInput.classList.add('is-invalid');
            hasError = true;
        } else {
            passwordError.style.display = 'none';
            confirmInput.classList.remove('is-invalid');
        }

        submitBtn.disabled = hasError;
    }

    passwordInput.addEventListener('input', validatePasswords);
    confirmInput.addEventListener('input', validatePasswords);

    // Reset validation state when opening modals
    function resetPasswordValidation() {
        submitBtn.disabled = false;
        passwordError.style.display = 'none';
        passwordLengthError.style.display = 'none';
        confirmInput.classList.remove('is-invalid');
        passwordInput.classList.remove('is-invalid');
    }

    function openCreateModal() {
        userForm.reset();
        userForm.action = "{{ route('user-management.store') }}";
        methodContainer.innerHTML = '';
        document.getElementById('modalTitle').innerText = 'Add User';
        document.getElementById('submitBtn').innerText  = 'Save User';
        document.getElementById('passwordRequired').style.display = 'inline';
        document.getElementById('passwordHint').style.display     = 'none';
        document.getElementById('passwordLengthHint').style.display = 'block';
        document.getElementById('confirmRequired').style.display = 'inline';
        document.getElementById('password').required = true;
        document.getElementById('password_confirmation').required = true;

        resetPasswordValidation();
        userModal.show();
    }

    function openEditModal(user) {
        userForm.reset();
        userForm.action = `/user-management/${user.user_id}`;
        methodContainer.innerHTML = '@method("PUT")';
        document.getElementById('modalTitle').innerText = 'Edit User';
        document.getElementById('submitBtn').innerText  = 'Update User';
        document.getElementById('passwordRequired').style.display = 'none';
        document.getElementById('passwordHint').style.display     = 'inline';
        document.getElementById('passwordLengthHint').style.display = 'none';
        document.getElementById('confirmRequired').style.display = 'none';
        document.getElementById('password').required = false;
        document.getElementById('password_confirmation').required = false;

        document.getElementById('first_name').value = user.first_name;
        document.getElementById('last_name').value  = user.last_name;
        document.getElementById('username').value   = user.username;
        document.getElementById('email').value      = user.email;
        document.getElementById('role_id').value    = user.role_id;
        document.getElementById('status').value     = user.status;

        resetPasswordValidation();
        userModal.show();
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete User?',
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