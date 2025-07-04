@extends('layouts.student')
@section('content')
<style>
    body.student-bg {
        background-color:rgb(28, 132, 166);
    }
    .dashboard-bg-icon {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 0;
        opacity: 0.08;
        font-size: 22vw;
        color: #1976d2;
        pointer-events: none;
        user-select: none;
    }
    .dashboard-widgets { position: relative; z-index: 1; }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('student-bg');
    });
</script>
<div class="dashboard-bg-icon">
    <i class="bi bi-journal-bookmark"></i>
</div>
@if($studentProfile)
<div class="row mb-6 dashboard-widgets">
    <!-- Profile Summary Widget -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-4">
                    @if($studentProfile->photo)
                        <img src="{{ asset('storage/' . $studentProfile->photo) }}" alt="Profile Photo" class="rounded-circle border" style="width:70px;height:70px;object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:70px;height:70px;font-size:2rem;">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h4 class="card-title mb-1">{{ $studentProfile->user->name ?? '-' }}</h4>
                    <p class="mb-1"><span class="badge bg-info text-dark">Student</span></p>
                    <ul class="list-unstyled mb-0 text-secondary">
                        <li><i class="bi bi-telephone me-2"></i><strong>Mobile:</strong> {{ $studentProfile->mobile ?? '-' }}</li>
                        @if(!empty($studentProfile->courses) && is_array($studentProfile->courses) && count($studentProfile->courses) > 0)
                            <li><i class="bi bi-journal me-2"></i><strong>Courses:</strong> {{ implode(', ', $studentProfile->courses) }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Seat Info Widget -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-chair me-2"></i>Seat Information</h5>
                <ul class="list-unstyled text-secondary mb-0">
                    <li><i class="bi bi-123 me-2"></i><strong>Seat Number:</strong> {{ $studentProfile->seat->number ?? 'Not Assigned' }}</li>
                    @if(!empty($studentProfile->timeslot_start))
                        <li><i class="bi bi-clock me-2"></i><strong>Timeslot:</strong> {{ $studentProfile->timeslot_start }} - {{ $studentProfile->timeslot_end }}</li>
                    @endif
                    <li><i class="bi bi-calendar-event me-2"></i><strong>Join Date:</strong> {{ $studentProfile->joining_date }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Attendance Widget -->
<div class="row mb-6 dashboard-widgets">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h5 class="card-title mb-2"><i class="bi bi-calendar-check me-2"></i>Attendance</h5>
                    <p class="mb-0">Check in and check out within your allowed timeslot.</p>
                </div>
                <div>
                    <form method="POST" action="{{ route('student.checkin') }}" class="d-inline-block me-2">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg px-4">Check In</button>
                    </form>
                    <form method="POST" action="{{ route('student.checkout') }}" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg px-4">Check Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
    <p class="font-bold">No Profile Found</p>
    <p>You have not joined the library yet. Please complete your profile to access dashboard features.</p>
</div>
@endif
<!-- Add more widgets as needed -->
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection 