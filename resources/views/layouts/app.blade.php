<!DOCTYPE html>
<html>

<head>
    <title>HEART System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: linear-gradient(180deg, #0a1f44 0%, #112b4e 100%);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            overflow-x: hidden;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar a.active-link {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
        }

        /* --- LOGO / HEADER STYLES --- */
        .sidebar h4 {
            font-weight: 700;
            font-size: 24px;
            margin-left: 15px;
            margin-bottom: 30px;
            letter-spacing: 0.5px;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .sidebar h4 i {
            margin-right: 12px; /* Space between heart and HEART text */
            transition: all 0.3s ease;
        }

        /* --- LINK STYLES --- */
        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 14px;
            white-space: nowrap;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            padding-left: 25px;
        }

        .sidebar hr {
            border-color: rgba(255, 255, 255, 0.2);
            margin: 20px 0;
        }

        /* --- COLLAPSIBLE OVERRIDES --- */
        .sidebar.collapsed {
            width: 75px !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* Center Logo Icon and hide 'HEART' text */
        .sidebar.collapsed h4 {
            margin-left: 0 !important; /* Remove original 15px margin */
            margin-right: 0 !important;
            justify-content: center; /* Flex center the icon */
            width: 100%; /* Take full width of collapsed sidebar */
        }

        .sidebar.collapsed h4 i {
            margin-right: 0 !important; /* Remove the 12px space between heart and text */
            font-size: 28px; /* Optional: Make the icon slightly bigger when collapsed */
        }

        /* Hide the span text */
        .sidebar.collapsed h4 span,
        .sidebar.collapsed a span,
        .sidebar.collapsed hr {
            display: none !important;
        }

        /* Center Nav Icons */
        .sidebar.collapsed a {
            justify-content: center;
            padding: 12px 0 !important;
            margin: 5px 10px !important;
        }

        .sidebar.collapsed a i {
            margin-right: 0 !important;
            font-size: 18px;
        }

        /* Force Logout Button to Square */
        .sidebar.collapsed #btn-logout-sidebar {
            width: 45px !important;
            margin: 0 auto !important;
            padding: 10px 0 !important;
            display: flex !important;
            justify-content: center;
            align-items: center;
        }

        .sidebar.collapsed #btn-logout-sidebar span {
            display: none !important;
        }

        .sidebar.collapsed #btn-logout-sidebar i {
            margin-right: 0 !important;
        }

        /* --- CONTENT AREA --- */
        .content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .content.expand {
            margin-left: 75px;
        }

        /* Other Navbar/Design styles exactly as provided */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 15px 30px;
            border-bottom: 3px solid #1e3a8a;
        }

        .navbar-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e3a8a;
            letter-spacing: 0.5px;
        }

        .container {
            padding: 30px;
        }

        h3 {
            color: #1e3a8a;
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 28px;
        }

        .btn {
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border: none;
        }

        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            font-size: 14px;
        }

        .table thead {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
        }

        /* Theme-matched Pagination Buttons */
        .pagination-custom .btn-outline-primary {
            color: #1e3a8a;
            border-color: #1e3a8a;
            background-color: white;
        }

        .pagination-custom .btn-outline-primary:hover {
            background-color: #1e3a8a;
            color: white;
        }

        .pagination-custom .btn-primary.active {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border-color: #1e3a8a;
            cursor: default;
        }

        .pagination-custom .btn-light.disabled {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            opacity: 0.6;
        }

        .text-danger {
            color: #dc3545 !important;
        }
        .form-label .required {
            margin-left: 3px;
        }

        .card-stat {
            padding: 25px;
            border-radius: 12px;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 100%; /* Ensures all cards in a row are the same height */
        }

        .card-stat:hover {
            transform: translateY(-5px);
        }

        .card-stat h6 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        .card-stat h2 {
            font-weight: 700;
            font-size: 32px;
        }

        .sidebar {
            /* ... existing styles ... */
            z-index: 1100 !important; /* Higher than your previous 1000 */
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: -250px; /* Hide off-screen by default on mobile */
            }

            /* This is the class the JS will toggle on mobile */
            .sidebar.mobile-show {
                left: 0 !important;
            }

            .content {
                margin-left: 0 !important; /* Remove margin on mobile so it's full width */
            }

            /* Optional: prevent content from shifting when sidebar slides over it */
            .content.expand {
                margin-left: 0 !important;
            }

            .container {
                padding: 15px; /* Slimmer padding for mobile screens */
            }
            
            .navbar-custom {
                padding: 10px 15px;
            }
        }
    </style>
</head>

<body>

    @include('layouts.sidebar')

    <div class="content">
        @include('layouts.navbar')
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');

            if (window.innerWidth <= 991) {
                // Mobile Logic: Slide the drawer in/out
                sidebar.classList.toggle('mobile-show');
            } else {
                // Desktop Logic: Your original collapse/expand
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expand');
            }
        }

        // Auto-close sidebar on mobile when clicking the main content
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const menuBtn = document.querySelector('.btn-light'); // The hamburger button

            if (window.innerWidth <= 991 && 
                sidebar.classList.contains('mobile-show') && 
                !sidebar.contains(event.target) && 
                !menuBtn.contains(event.target)) {
                sidebar.classList.remove('mobile-show');
            }
        });
    </script>

    @stack('scripts')

</body>

</html>