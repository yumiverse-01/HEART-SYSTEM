<div class="topbar-left">
    <button class="topbar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <span class="topbar-title">Barangay Portal</span>
</div>

<div class="topbar-user">
    @if(auth()->check())
        <strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</strong>
    @endif
    <div class="topbar-avatar">
        {{ substr(auth()->user()->first_name ?? 'A', 0, 1) }}
    </div>
</div>