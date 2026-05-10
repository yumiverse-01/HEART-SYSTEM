<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEART System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        :root {
            --navy: #0a1f44;
            --navy-mid: #112b4e;
            --primary: #1e3a8a;
            --sidebar-w: 250px;
            --sidebar-collapsed: 70px;
            --topbar-h: 56px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, var(--navy) 0%, var(--navy-mid) 100%);
            color: white;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, width 0.3s ease;
            z-index: 1050;
            overflow: hidden;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 20px 14px;
            font-size: 1.25rem;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .sidebar-brand i { font-size: 1.3rem; color: #f87171; }

        .sidebar-divider {
            border-color: rgba(255,255,255,0.15);
            margin: 0 16px 8px;
            flex-shrink: 0;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 4px 10px;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            padding: 11px 14px;
            border-radius: 8px;
            margin-bottom: 2px;
            font-size: 0.875rem;
            white-space: nowrap;
            transition: background 0.2s, color 0.2s;
        }
        .sidebar-nav a i { width: 18px; text-align: center; flex-shrink: 0; font-size: 1rem; }
        .sidebar-nav a:hover, .sidebar-nav a.active-link {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }

        .sidebar-footer {
            padding: 12px 10px;
            flex-shrink: 0;
        }
        .sidebar-footer hr { border-color: rgba(255,255,255,0.15); margin-bottom: 10px; }
        #btn-logout-sidebar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
        }

        /* COLLAPSED (desktop) */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }
        .sidebar.collapsed .sidebar-brand span,
        .sidebar.collapsed .sidebar-nav a span,
        .sidebar.collapsed #btn-logout-sidebar span {
            display: none;
        }
        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
            padding: 18px 0 14px;
        }
        .sidebar.collapsed .sidebar-nav a {
            justify-content: center;
            padding: 12px 0;
        }
        .sidebar.collapsed #btn-logout-sidebar {
            justify-content: center;
        }

        /* MOBILE: off-canvas */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-w) !important;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            /* Overlay */
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.45);
                z-index: 1040;
            }
            .sidebar-overlay.active { display: block; }
        }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1030;
            transition: left 0.3s ease;
            gap: 12px;
        }
        .topbar.expanded { left: var(--sidebar-collapsed); }

        @media (max-width: 991.98px) {
            .topbar { left: 0 !important; }
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-title { font-size: 0.95rem; font-weight: 700; color: var(--primary); white-space: nowrap; }

        .topbar-toggle {
            background: none;
            border: none;
            padding: 6px 8px;
            border-radius: 6px;
            color: #475569;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .topbar-toggle:hover { background: #f1f5f9; }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #475569;
            white-space: nowrap;
            overflow: hidden;
        }
        .topbar-user strong { 
            display: none; 
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media (min-width: 576px) {
            .topbar-user strong { display: inline; }
        }
        .topbar-avatar {
            width: 34px; height: 34px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded { margin-left: var(--sidebar-collapsed); }

        @media (max-width: 991.98px) {
            .main-content { margin-left: 0 !important; }
        }

        .page-body {
            padding: 20px 16px;
        }
        @media (min-width: 768px) {
            .page-body { padding: 28px 24px; }
        }

        /* ── CARDS ── */
        .card { border-radius: 12px; }
        .card-stat {
            padding: 18px;
            border-radius: 12px;
            color: white;
            height: 100%;
        }
        .card-stat h6 {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            opacity: 0.85;
            margin-bottom: 6px;
        }
        .card-stat h2 {
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            font-weight: 700;
            margin: 0;
        }
        .card-stat .stat-icon {
            font-size: 2rem;
            opacity: 0.25;
            float: right;
            margin-top: -30px;
        }

        /* ── TABLES (mobile) ── */
        .table-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        }

        /* On small screens, show table as stacked cards */
        @media (max-width: 767.98px) {
            .mobile-stack thead { display: none; }
            .mobile-stack tbody tr {
                display: block;
                background: #fff;
                border-radius: 10px;
                margin-bottom: 10px;
                padding: 12px 14px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.07);
                border: none !important;
            }
            .mobile-stack tbody td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 8px;
                border: none !important;
                padding: 5px 0 !important;
                font-size: 0.85rem;
                flex-wrap: wrap;
            }
            .mobile-stack tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                color: #94a3b8;
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                min-width: 90px;
                flex-shrink: 0;
                padding-top: 2px;
            }
            .mobile-stack tbody td.actions-cell {
                justify-content: flex-end;
                padding-top: 10px !important;
            }
            .mobile-stack tbody td.actions-cell::before { display: none; }
        }

        /* ── BUTTONS ── */
        .btn { border-radius: 8px; font-weight: 600; }
        .btn-primary {
            background: linear-gradient(135deg, #1e3a8a, #1e40af);
            border: none;
        }
        .btn-primary:hover { background: linear-gradient(135deg, #1e40af, #2563eb); }

        /* ── PAGINATION ── */
        .pagination-custom .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .pagination-custom .btn-outline-primary:hover { background: var(--primary); color: #fff; }
        .pagination-custom .btn-primary.active { background: var(--primary); border-color: var(--primary); }

        /* ── MODALS ── */
        .modal {
            overflow-x: hidden;
            overflow-y: auto !important;
        }

        /* ── MISC ── */
        h3 { color: var(--primary); font-weight: 700; font-size: clamp(1.1rem, 3vw, 1.5rem); }
        .text-navy { color: var(--primary) !important; }

        /* Page header row */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 18px;
        }
        .page-header h3 { margin: 0; }
    </style>
</head>
<body>

    {{-- Sidebar overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('layouts.sidebar')

    <div class="topbar" id="topbar">
        @include('layouts.navbar')
    </div>

    <div class="main-content" id="mainContent">
        <div class="page-body">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar      = document.getElementById('sidebar');
        const topbar       = document.getElementById('topbar');
        const mainContent  = document.getElementById('mainContent');
        const overlay      = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (window.innerWidth <= 991) {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                topbar.classList.toggle('expanded');
                mainContent.classList.toggle('expanded');
            }
        }

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        });

        // Close mobile sidebar on nav link click
        document.querySelectorAll('.sidebar-nav a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 991) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>