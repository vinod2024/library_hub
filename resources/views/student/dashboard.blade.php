@extends('layouts.student')
@section('content')
<style>
    .dashboard-bg-icon {
        
    }
    .dashboard-widgets { position: relative; z-index: 1; }
    .alert {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-checkin {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-checkin:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(40, 167, 69, 0.3);
    }
    .btn-checkout {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(220, 53, 69, 0.3);
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    });
</script>


<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
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
                        <li><i class="bi bi-envelope me-2"></i><strong>Email:</strong> {{ $studentProfile->user->email ?? '-' }}</li>
                        <li><i class="bi bi-paypal me-2"></i><strong>Next Pay Date:</strong> {{ Carbon\Carbon::parse($studentProfile->joining_date)->addMonth()->subDay()->format('d-m-Y') }}</li>
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
                        <li><i class="bi bi-clock me-2"></i><strong>Timeslot:</strong> {{ Carbon\Carbon::parse($studentProfile->timeslot_start)->format('h:i A') }} - {{ Carbon\Carbon::parse($studentProfile->timeslot_end)->format('h:i A') }}</li>
                    @endif
                    <li><i class="bi bi-calendar-event me-2"></i><strong>Join Date:</strong> {{ Carbon\Carbon::parse($studentProfile->joining_date)->format('d-m-Y') }}</li>
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
                    @php
                        $todayTimesheet = \App\Models\Timesheet::where('student_profile_id', $studentProfile->id)
                            ->where('date', now()->toDateString())
                            ->orderBy('id', 'desc')
                            ->first();
                    @endphp
                    @if($todayTimesheet)
                        <div class="mt-2">
                            @if($todayTimesheet->check_in)
                                <span class="badge bg-success me-2">
                                    <i class="bi bi-check-circle me-1"></i>Checked In: {{ \Carbon\Carbon::parse($todayTimesheet->check_in)->format('H:i') }}
                                </span>
                            @endif
                            @if($todayTimesheet->check_out)
                                <span class="badge bg-info">
                                    <i class="bi bi-box-arrow-left me-1"></i>Checked Out: {{ \Carbon\Carbon::parse($todayTimesheet->check_out)->format('H:i') }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
                <div>
                    @php
                        $todayTimesheet = \App\Models\Timesheet::where('student_profile_id', $studentProfile->id)
                            ->where('date', now()->toDateString())
                            ->orderBy('id', 'desc')
                            ->first();
                        $isCheckedIn = $todayTimesheet && $todayTimesheet->check_in;
                        $isCheckedOut = $todayTimesheet && $todayTimesheet->check_out;
                    @endphp
                    
                    <form method="POST" action="{{ route('student.checkin') }}" class="d-inline-block me-2">
                        @csrf
                        <button type="submit" class="btn btn-checkin btn-lg px-4" 
                                {{ $isCheckedIn && !$isCheckedOut ? 'disabled' : '' }}>
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            {{ $isCheckedIn ? 'Already Checked In' : 'Check In' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('student.checkout') }}" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-checkout btn-lg px-4" 
                                {{ !$isCheckedIn || $isCheckedOut ? 'disabled' : '' }}>
                            <i class="bi bi-box-arrow-left me-2"></i>
                            {{ $isCheckedOut ? 'Already Checked Out' : 'Check Out' }}
                        </button>
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