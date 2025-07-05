@extends('layouts.student')
@section('content')
<style>
    .join-form-bg {
        background: linear-gradient(135deg, #e3f0ff 0%, #b3d8fd 100%);
        border-radius: 1rem;
        box-shadow: 0 2px 16px rgba(0,0,0,0.08);
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
    }
    .join-form-header {
        background: #1976d2;
        color: #fff;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        padding: 1.5rem 1rem 1rem 1rem;
        margin: -2rem -1.5rem 2rem -1.5rem;
        box-shadow: 0 2px 8px rgba(25,118,210,0.08);
    }
    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1976d2;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    .form-section-title i {
        margin-right: 0.5rem;
    }
    .btn-join {
        background: #1976d2;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        padding: 0.75rem 2.5rem;
        font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(25,118,210,0.08);
        transition: background 0.2s;
    }
    .btn-join:hover {
        background: #125ea2;
        color: #fff;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<div class="join-form-bg">
    <div class="join-form-header">
        <h2 class="mb-0"><i class="bi bi-person-plus me-2"></i>Join Library</h2>
        <p class="mb-0">Fill out the form below to join the library and reserve your seat.</p>
    </div>
    <form method="POST" action="{{ route('student.join.submit') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-section-title"><i class="bi bi-person-lines-fill"></i>Personal Information</div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="mobile" class="form-label">Mobile</label>
                <input type="text" class="form-control" id="mobile" name="mobile" required>
            </div>
            <div class="col-md-6">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo">
            </div>
            <div class="col-md-6">
                <label for="id_proof" class="form-label">ID Proof</label>
                <input type="file" class="form-control" id="id_proof" name="id_proof">
            </div>
        </div>
        <div class="form-section-title"><i class="bi bi-clock-history"></i>Library Usage</div>
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="timeslot_start" class="form-label">Timeslot Start</label>
                <input type="time" class="form-control" id="timeslot_start" name="timeslot_start" required>
            </div>
            <div class="col-md-4">
                <label for="timeslot_end" class="form-label">Timeslot End</label>
                <input type="time" class="form-control" id="timeslot_end" name="timeslot_end" required>
            </div>
            <div class="col-md-4">
                <label for="joining_date" class="form-label">Joining Date</label>
                <input type="date" class="form-control" id="joining_date" name="joining_date" required>
            </div>
        </div>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-join"><i class="bi bi-check2-circle me-2"></i>Join</button>
        </div>
    </form>
</div>
@endsection 