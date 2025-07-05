@extends('layouts.student')
@section('content')
<style>
    body.student-bg {
        background-color:rgb(28, 132, 166);
    }
    .seat-selection-bg {
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .seat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .seat-item {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.5rem;
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
        font-size: 1rem;
        font-weight: bold;
        color: #495057;
    }
    .seat-status {
        font-size: 0.7rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    .timeslot-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
    .debug-info {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 1rem;
        margin-bottom: 1rem;
        font-family: monospace;
        font-size: 0.9rem;
    }
</style>

<script>
    function validateAndSubmit() {
        const selectedSeat = document.querySelector('input[name="seat_id"]:checked');
        const form = document.getElementById('seat-selection-form');
        if (!selectedSeat) {
            alert('Please select a seat before confirming.');
            return false;
        }
        
        return true;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('student-bg');
        
        // Handle seat selection
        const seatItems = document.querySelectorAll('.seat-item');
        const confirmBtn = document.getElementById('confirm-seat-btn');
        const selectedSeatInfo = document.getElementById('selected-seat-info');
        const selectedSeatNumber = document.getElementById('selected-seat-number');
        
        // Handle radio button changes
        const radioButtons = document.querySelectorAll('input[name="seat_id"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                console.log('Radio button changed:', this.value);
                
                // Remove previous selection
                seatItems.forEach(seat => seat.classList.remove('selected'));
                
                // Add selection to clicked seat
                const seatItem = this.closest('.seat-item');
                seatItem.classList.add('selected');
                
                // Enable confirm button
                confirmBtn.disabled = false;
                
                // Show selected seat info
                selectedSeatInfo.style.display = 'block';
                selectedSeatNumber.textContent = seatItem.dataset.seatNumber;
                
                console.log('Seat selected:', this.value, 'Number:', seatItem.dataset.seatNumber);
            });
        });

        // Form validation and submission
        const form = document.getElementById('seat-selection-form');
        form.addEventListener('submit', function(e) {
            const selectedSeat = document.querySelector('input[name="seat_id"]:checked');
            
            
            if (!selectedSeat) {
                e.preventDefault();
                alert('Please select a seat before confirming.');
                return false;
            }
            
        });
    });
</script>

<div class="seat-selection-bg">
    <div class="text-center mb-4">
        <h2 class="mb-3"><i class="bi bi-chair me-2"></i>Select Your Seat</h2>
    </div>
    

    <!-- Timeslot Information -->
    <div class="timeslot-info">
        <h5><i class="bi bi-clock me-2"></i>Your Timeslot</h5>
        <p class="mb-0">
            <strong>{{ Carbon\Carbon::parse($studentProfile->timeslot_start)->format('h:i A') }}</strong> - <strong>{{ Carbon\Carbon::parse($studentProfile->timeslot_end)->format('h:i A') }}</strong>
        </p>
        <small>Check-in time: {{ Carbon\Carbon::now()->format('h:i A') }}</small>
    </div>

    <!-- Available Seats -->
    <div class="row">
        <div class="col-12">
            <h5 class="mb-2"><i class="bi bi-grid me-2"></i>Available Seats ({{ $availableSeats->count() }})</h5>
            
            @if($availableSeats->count() > 0)
                <form method="POST" action="{{ route('student.checkin') }}" id="seat-selection-form">
                    @csrf
                    
                    <div class="seat-grid">
                        @foreach($availableSeats as $seat)
                            <div class="seat-item" data-seat-id="{{ $seat->id }}" data-seat-number="{{ $seat->number }}">
                                <input type="radio" name="seat_id" value="{{ $seat->id }}" id="seat_{{ $seat->id }}" style="display: none;">
                                <label for="seat_{{ $seat->id }}" style="cursor: pointer; display: block;">
                                    <div class="seat-number">{{ $seat->number }}</div>
                                    <div class="seat-status">Available</div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center mt-3">
                        <div id="selected-seat-info" class="alert alert-info mb-3" style="display: none;">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Selected Seat:</strong> <span id="selected-seat-number"></span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg px-5" id="confirm-seat-btn" disabled onclick="return validateAndSubmit()">
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