<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="font-bold">Library Admin</a>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="mr-4">Dashboard</a>
                <a href="{{ route('admin.users.index') }}" class="mr-4">Users</a>
                <a href="{{ route('admin.seats.index') }}" class="mr-4">Seats</a>
                <a href="{{ route('admin.students.index') }}" class="mr-4">Students</a>
                <a href="{{ route('admin.reports.index') }}" class="mr-4">Reports</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="ml-4">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <main class="container mx-auto mt-8">
        @yield('content')
    </main>
</body>
</html> 