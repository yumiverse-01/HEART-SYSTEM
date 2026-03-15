<div class="navbar-custom d-flex justify-content-between align-items-center">

    <div class="d-flex align-items-center">
        <button onclick="toggleSidebar()" class="btn btn-light me-3">
            <i class="fas fa-bars"></i>
        </button>
        <span class="navbar-title">Health Worker Portal</span>
    </div>

    <div class="d-flex align-items-center">
        @if(auth()->check())
            <span class="me-3">
                <i class="fas fa-user-circle"></i> Welcome, <strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</strong>
            </span>
        @endif
    </div>

</div>