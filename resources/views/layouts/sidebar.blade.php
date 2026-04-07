<div class="sidebar d-flex flex-column p-3">

<h4>❤ HEART</h4>

<hr>

<div class="flex-grow-1">
    @if(in_array(Auth::user()->role?->name, ['Super Admin', 'Admin']))
        <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active-link' : '' }}"><i class="fas fa-chart-line me-2"></i>Dashboard</a>
        <a href="/admin/reports" class="{{ request()->is('admin/reports*') ? 'active-link' : '' }}"><i class="fas fa-file-alt me-2"></i>Reports</a>
        <a href="/admin/staff-activities" class="{{ request()->is('admin/staff-activities*') ? 'active-link' : '' }}"><i class="fas fa-users-cog me-2"></i>Staff Activities</a>
        <a href="/admin/user-management" class="{{ request()->is('admin/user-management*') ? 'active-link' : '' }}"><i class="fas fa-user-shield me-2"></i>User Management</a>
    @else
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active-link' : '' }}"><i class="fas fa-chart-line me-2"></i>Dashboard</a>
        @can('view-beneficiaries')
            <a href="/beneficiaries" class="{{ request()->is('beneficiaries*') ? 'active-link' : '' }}"><i class="fas fa-users me-2"></i>Beneficiaries</a>
        @endcan
        @can('view-events')
            <a href="/events" class="{{ request()->is('events*') ? 'active-link' : '' }}"><i class="fas fa-calendar-alt me-2"></i>Outreach Events</a>
        @endcan
        @can('view-attendance')
            <a href="/attendance" class="{{ request()->is('attendance*') ? 'active-link' : '' }}"><i class="fas fa-clipboard-list me-2"></i>Attendance</a>
        @endcan
        @can('view-service-records')
            <a href="/service-records" class="{{ request()->is('service*') ? 'active-link' : '' }}"><i class="fas fa-heartbeat me-2"></i>Health Services</a>
        @endcan
        @can('view-reports')
            <a href="/reports" class="{{ request()->is('reports*') ? 'active-link' : '' }}"><i class="fas fa-file-alt me-2"></i>Reports</a>
        @endcan
    @endif
</div>

<hr>

@if(in_array(Auth::user()->role?->name, ['Super Admin', 'Admin']))
    <a href="{{ url('/admin/logout') }}" class="btn btn-danger d-block mx-auto" style="width: 100%;">Logout</a>
@else
    <a href="{{ url('/logout') }}" class="btn btn-danger d-block mx-auto" style="width: 100%;">Logout</a>
@endif

</div>