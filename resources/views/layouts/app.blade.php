<!DOCTYPE html>
<html>
<head>
    <title>Product Admin</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global CSRF Setup for all AJAX calls
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    </script>

    <style>
        body {
            margin: 0;
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background-color: #212529;
            position: fixed;
            width: 220px;
        }

        .sidebar h4 {
            color: white;
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #444;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #adb5bd;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #343a40;
            color: #fff;
        }

        .main-content {
            margin-left: 220px;
        }

        .topbar {
            background: #fff;
            padding: 12px 20px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Admin Panel</h4>

    <a href="{{ route('products.index') }}"
       class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
        List Products
    </a>
    {{-- <a href="{{ route('products.create') }}"
       class="{{ request()->routeIs('products.create') ? 'active' : '' }}">
        Add Product
    </a> --}}

  
</div>

<!-- Main Content -->
<div class="main-content">

    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Product Management</h5>
        <span>Welcome, Admin</span>
    </div>

    <div class="p-4">
        @yield('content')
    </div>

</div>

</body>
</html>