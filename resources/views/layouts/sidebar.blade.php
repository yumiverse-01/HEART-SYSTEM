<div class="sidebar d-flex flex-column p-3">
    <div class="d-flex justify-content-between align-items-center m-1">
        <h4 class="m-0"><i class="fas fa-heart-pulse"></i> <span> HEART</span></h4>
        <button type="button" class="btn btn-sm text-white d-lg-none" onclick="toggleSidebar()">
            <i class="fas fa-times" style="font-size: 20px;"></i>
        </button>
    </div>
    <hr>

    <div class="flex-grow-1">
        @if(in_array(Auth::user()->role?->name, ['Super Admin', 'Admin']))
            <a href="/admin" class="{{ request()->is('admin') ? 'active-link' : '' }}">
                <i class="fas fa-chart-line me-2"></i><span>Dashboard</span>
            </a>
        @else
            <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active-link' : '' }}">
                <i class="fas fa-chart-line me-2"></i><span>Dashboard</span>
            </a>
        @endif

        @can('view-beneficiaries')
            <a href="/beneficiaries" class="{{ request()->is('beneficiaries*') ? 'active-link' : '' }}">
                <i class="fas fa-users me-2"></i><span>Beneficiaries</span>
            </a>
        @endcan

        @can('view-events')
            <a href="/events" class="{{ request()->is('events*') ? 'active-link' : '' }}">
                <i class="fas fa-calendar-alt me-2"></i><span>Outreach Events</span>
            </a>
        @endcan

        @can('view-attendance')
            <a href="/attendance" class="{{ request()->is('attendance*') ? 'active-link' : '' }}">
                <i class="fas fa-clipboard-list me-2"></i><span>Attendance</span>
            </a>
        @endcan

        @can('view-service-records')
            <a href="/service-records" class="{{ request()->is('service*') ? 'active-link' : '' }}">
                <i class="fas fa-heartbeat me-2"></i><span>Health Services</span>
            </a>
        @endcan

        @can('view-reports')
            <a href="/reports" class="{{ request()->is('reports*') ? 'active-link' : '' }}">
                <i class="fas fa-file-alt me-2"></i><span>Reports</span>
            </a>
        @endcan

        @can('access-admin')
            <a href="/staff-activities" class="{{ request()->is('staff-activities*') ? 'active-link' : '' }}">
                <i class="fas fa-users-cog me-2"></i><span>Staff Activities</span>
            </a>
        @endcan

        @can('manage-users')
            <a href="/user-management" class="{{ request()->is('user-management*') ? 'active-link' : '' }}">
                <i class="fas fa-user-shield me-2"></i><span>User Management</span>
            </a>
        @endcan
    </div>

    <hr>
    
    <button type="button" id="btn-logout-sidebar" class="btn btn-danger d-block w-100">
        <i class="fas fa-sign-out-alt me-2"></i><span>Logout</span>
    </button>

    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="GET" style="display: none;">
        @csrf
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btn-logout-sidebar').addEventListener('click', function() {
        Swal.fire({
            title: 'Logout Session?',
            text: "Are you sure you want to exit the portal?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Logout'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form-sidebar').submit();
            }
        });
    });
</script>
@endpush