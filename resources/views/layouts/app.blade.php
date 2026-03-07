<!DOCTYPE html>
<html>

<head>

<title>HEART System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
  box-shadow: 2px 0 10px rgba(0,0,0,0.1);
  overflow-y: auto;
  padding-top: 20px;
  z-index: 1000;
}

.sidebar a.active-link {
  background: rgba(255,255,255,0.2);
  border-radius: 6px;
}

.sidebar h4 {
  font-weight: 700;
  font-size: 24px;
  margin-left: 15px;
  margin-bottom: 30px;
  letter-spacing: 0.5px;
}

.sidebar a {
  display: block;
  color: white;
  text-decoration: none;
  padding: 12px 20px;
  margin: 5px 10px;
  border-radius: 6px;
  transition: all 0.3s ease;
  font-size: 14px;
}

.sidebar a:hover {
  background-color: rgba(255,255,255,0.2);
  padding-left: 25px;
}

.sidebar hr {
  border-color: rgba(255,255,255,0.2);
  margin: 20px 0;
}

.sidebar.collapsed {
  width: 70px;
}

.sidebar.collapsed h4,
.sidebar.collapsed a,
.sidebar.collapsed hr {
  display: none;
}

.content {
  margin-left: 250px;
  transition: margin-left 0.3s ease;
  min-height: 100vh;
}

.content.expand {
  margin-left: 70px;
}

.navbar-custom {
  background: white;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
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

.btn-primary:hover {
  background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(30, 58, 138, 0.3);
}

.btn-danger {
  background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
  border: none;
}

.btn-danger:hover {
  background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
  transform: translateY(-2px);
}

.btn-outline-danger {
  color: #dc2626;
  border-color: #dc2626;
}

.btn-outline-danger:hover {
  background: #dc2626;
  border-color: #dc2626;
}

.table {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  font-size: 14px;
}

.table thead {
  background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
  color: white;
  font-weight: 600;
}

.table tbody tr:hover {
  background-color: #f0f4f8;
}

.table td, .table th {
  vertical-align: middle;
  padding: 15px;
  border-color: #e5e7eb;
}

.table .btn-sm {
  padding: 5px 10px;
  font-size: 12px;
  margin: 2px;
}

.card {
  border: none;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.12);
}

.card-stat {
  border-radius: 8px;
  color: white;
  padding: 25px;
  font-weight: 600;
  background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
}

.card-stat h5 {
  font-size: 32px;
  margin-bottom: 10px;
}

.form-control, .form-select {
  border-radius: 6px;
  border: 1px solid #d1d5db;
  padding: 10px 15px;
  font-size: 14px;
  transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
  border-color: #1e3a8a;
  box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
}

.form-label {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 8px;
  font-size: 14px;
}

.card-body {
  padding: 25px;
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

function toggleSidebar(){

document.querySelector('.sidebar').classList.toggle('collapsed')

document.querySelector('.content').classList.toggle('expand')

}

</script>

@stack('scripts')

</body>

</html>