@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<div class="mb-4">
    <h2 class="fw-bold mb-3"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h2>
    <p class="text-secondary">Welcome to the library management admin panel. Here are your key stats and quick links.</p>
</div>
<div class="row g-4 mb-4">
    <!-- Total Users Widget -->
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0 text-center bg-light">
            <div class="card-body">
                <div class="mb-2"><i class="bi bi-people-fill text-primary" style="font-size:2rem;"></i></div>
                <h5 class="card-title">Total Users</h5>
                <div class="display-6 fw-bold">{{ $totalUsers ?? '0' }}</div>
            </div>
        </div>
    </div>
    <!-- Total Students Widget -->
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0 text-center bg-info-subtle">
            <div class="card-body">
                <div class="mb-2"><i class="bi bi-person-badge text-info" style="font-size:2rem;"></i></div>
                <h5 class="card-title">Total Students</h5>
                <div class="display-6 fw-bold">{{ $totalStudents ?? '0' }}</div>
            </div>
        </div>
    </div>
    <!-- Total Seats Widget -->
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0 text-center bg-success-subtle">
            <div class="card-body">
                <div class="mb-2"><i class="bi bi-grid-3x3-gap-fill text-success" style="font-size:2rem;"></i></div>
                <h5 class="card-title">Total Seats</h5>
                <div class="display-6 fw-bold">{{ $totalSeats ?? '0' }}</div>
            </div>
        </div>
    </div>
    <!-- Vacant Seats Widget -->
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0 text-center bg-warning-subtle">
            <div class="card-body">
                <div class="mb-2"><i class="bi bi-emoji-smile text-warning" style="font-size:2rem;"></i></div>
                <h5 class="card-title">Vacant Seats</h5>
                <div class="display-6 fw-bold">{{ $vacantSeats ?? '0' }}</div>
            </div>
        </div>
    </div>
</div>
<!-- Seating Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-grid-3x3-gap me-2"></i>Vacant Seats</h5>
            </div>
            <div class="card-body">
                <div class="row g-1">
                    @foreach($vacantSeatsList ?? [] as $seat)
                    <div class="col-md-1 col-sm-1 col-2 col-3">
                        <div class="card border-success text-center" style="min-height: 40px;">
                            <div class="card-body py-1 px-0">
                                <div class="fw-bold text-success" style="font-size: 0.9rem;">{{ $seat->number }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if(empty($vacantSeatsList))
                    <div class="col-12 text-center text-muted">
                        <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                        <p class="mt-1 mb-0">No vacant seats</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add more widgets or quick links as needed -->
@endsection 