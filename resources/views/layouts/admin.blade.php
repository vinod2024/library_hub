<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body.admin-bg {
            background-color: #e3f0ff;
            background-image:
                linear-gradient(135deg, #e3f0ff 0%, #1976d2 100%),
                
            background-repeat: repeat;
            background-size: 180px 180px, 20px 20px;
            min-height: 100vh;
        }
        .admin-content-bg {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
            padding: 2rem 1.5rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .admin-navbar {
            background: linear-gradient(90deg, #1976d2 0%, #63a4ff 100%);
            box-shadow: 0 2px 8px rgba(25,118,210,0.08);
        }
        .admin-footer {
            background: linear-gradient(90deg, #1976d2 0%, #63a4ff 100%);
            color: #fff;
            box-shadow: 0 -2px 8px rgba(25,118,210,0.08);
        }
        .admin-logo {
            width: 38px;
            height: 38px;
            margin-right: 10px;
            vertical-align: middle;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 1.3rem;
            letter-spacing: 1px;
        }
    </style>
</head>
<body class="admin-bg">
    <!-- Header/Navbar -->
    <nav class="navbar navbar-expand-lg admin-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <!-- Sample SVG Logo -->
                <svg class="admin-logo" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="40" height="40" rx="8" fill="#fff"/>
                    <path d="M10 30V12a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v18" stroke="#1976d2" stroke-width="2" fill="none"/>
                    <rect x="14" y="16" width="12" height="8" rx="2" fill="#1976d2"/>
                </svg>
                Library Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.users.index') }}">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.seats.index') }}">Seats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.students.index') }}">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.reports.index') }}">Reports</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-white" style="display:inline;cursor:pointer;">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Main Content -->
    <main class="container py-4 min-vh-100">
        <div class="admin-content-bg">
            @yield('content')
        </div>
    </main>
    <!-- Footer -->
    <footer class="admin-footer text-center py-3 mt-auto">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <svg class="admin-logo" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="40" height="40" rx="8" fill="#fff"/>
                    <path d="M10 30V12a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v18" stroke="#1976d2" stroke-width="2" fill="none"/>
                    <rect x="14" y="16" width="12" height="8" rx="2" fill="#1976d2"/>
                </svg>
                <span class="ms-2">Library Management System</span>
            </div>
            <div>
                &copy; {{ date('Y') }} All rights reserved.
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 