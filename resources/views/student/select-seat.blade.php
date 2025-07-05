@extends('layouts.student')
@section('content')
<style>
    body.student-bg {
        background-color:rgb(28, 132, 166);
    }
    .seat-selection-bg {
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .seat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }
    .seat-item {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }
    .seat-item:hover {
        border-color: #007bff;
        background-color: #e3f2fd;
        transform: translateY(-2px);
    }
    .seat-item.selected {
        border-color: #28a745;
        background-color: #d4edda;
    }
    .seat-number {
        font-size: 1.2rem;
        font-weight: bold;
        color: #495057;
    }
    .seat-status {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    .timeslot-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('student-bg');
        
        // Handle seat selection
        const seatItems = document.querySelectorAll('.seat-item');
        const confirmBtn = document.getElementById('confirm-seat-btn');
        const selectedSeatInput = document.getElementById('selected_seat_id');
        
        seatItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove previous selection
                seatItems.forEach(seat => seat.classList.remove('selected'));
                // Add selection to clicked seat
                this.classList.add('selected');
                
                // Update hidden input
                selectedSeatInput.value = this.dataset.seatId;
                
                // Enable confirm button
                confirmBtn.disabled = false;
            });
        });

        // Simple form validation
        const form = document.getElementById('seat-selection-form');
        form.addEventListener('submit', function(e) {
            console.log('Form submission event triggered');
            console.log('Selected seat value:', selectedSeatInput.value);
            console.log('Form method:', form.method);
            console.log('Form action:', form.action);
            
            if (!selectedSeatInput.value) {
                e.preventDefault();
                alert('Please select a seat before confirming.');
                return false;
            }
            console.log('Form validation passed, submitting with seat:', selectedSeatInput.value);
        });
</script>

<div class="seat-selection-bg">
    <div class="text-center mb-4">
        <h2 class="mb-3"><i class="bi bi-chair me-2"></i>Select Your Seat</h2>
        <p class="text-muted">Choose an available seat for your library session</p>
    </div>

    <!-- Timeslot Information -->
    <div class="timeslot-info">
        <h5><i class="bi bi-clock me-2"></i>Your Timeslot</h5>
        <p class="mb-0">
            <strong>{{ $studentProfile->timeslot_start }}</strong> - <strong>{{ $studentProfile->timeslot_end }}</strong>
        </p>
        <small>Check-in time: {{ now()->format('H:i') }}</small>
    </div>

    <!-- Available Seats -->
    <div class="row">
        <div class="col-12">
            <h5 class="mb-3"><i class="bi bi-grid me-2"></i>Available Seats ({{ $availableSeats->count() }})</h5>
            
            @if($availableSeats->count() > 0)
                <form method="POST" action="{{ route('student.checkin') }}" id="seat-selection-form">
                    @csrf
                    <input type="hidden" name="seat_id" id="selected_seat_id" required>
                    
                    <div class="seat-grid">
                        @foreach($availableSeats as $seat)
                            <div class="seat-item" data-seat-id="{{ $seat->id }}">
                                <div class="seat-number">{{ $seat->number }}</div>
                                <div class="seat-status">Available</div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5" id="confirm-seat-btn" disabled>
                            <i class="bi bi-check-circle me-2"></i>Confirm Seat & Check In
                        </button>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-lg px-5 ms-2">
                            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </form>
            @else
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    No seats are currently available. Please try again later.
                </div>
                <div class="text-center">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>



<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection 