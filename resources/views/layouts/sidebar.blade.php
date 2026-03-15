<div class="sidebar d-flex flex-column p-3">

<h4>❤ HEART</h4>

<hr>

<div class="flex-grow-1">
    <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active-link' : '' }}"><i class="fas fa-chart-line me-2"></i>Dashboard</a>
    @can('view-beneficiaries')
        <a href="/beneficiaries" class="{{ request()->is('beneficiaries*') ? 'active-link' : '' }}"><i class="fas fa-users me-2"></i>Beneficiaries</a>
    @endcan
    @can('view-outreach-events')
        <a href="/events" class="{{ request()->is('events*') ? 'active-link' : '' }}"><i class="fas fa-calendar-alt me-2"></i>Outreach Events</a>
    @endcan
    @can('view-attendance')
        <a href="/attendance" class="{{ request()->is('attendance*') ? 'active-link' : '' }}"><i class="fas fa-clipboard-list me-2"></i>Attendance</a>
    @endcan
    @can('view-health-services')
        <a href="/service-records" class="{{ request()->is('service*') ? 'active-link' : '' }}"><i class="fas fa-heartbeat me-2"></i>Health Services</a>
    @endcan
    @can('view-reports')
        <a href="/reports" class="{{ request()->is('reports*') ? 'active-link' : '' }}"><i class="fas fa-file-alt me-2"></i>Reports</a>
    @endcan
</div>

<hr>

<a href="{{ url('/logout') }}" class="btn btn-danger w-100 mt-auto">Logout</a>

</div>