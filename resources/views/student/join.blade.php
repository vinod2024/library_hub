@extends('layouts.student')
@section('content')
<div class="container mt-4">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Join Library</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('student.join.submit') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo">
                </div>
                <div class="mb-3">
                    <label for="id_proof" class="form-label">ID Proof</label>
                    <input type="file" class="form-control" id="id_proof" name="id_proof">
                </div>
                <div class="mb-3">
                    <label for="courses" class="form-label">Courses</label>
                    <select class="form-select" id="courses" name="courses[]" multiple required>
                        <option value="BSc">BSc</option>
                        <option value="BA">BA</option>
                        <option value="BCom">BCom</option>
                        <option value="MSc">MSc</option>
                        <option value="MA">MA</option>
                        <option value="MCom">MCom</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="purpose" class="form-label">Purpose</label>
                    <input type="text" class="form-control" id="purpose" name="purpose" required>
                </div>
                <div class="mb-3">
                    <label for="timeslot_start" class="form-label">Timeslot Start</label>
                    <input type="time" class="form-control" id="timeslot_start" name="timeslot_start" required>
                </div>
                <div class="mb-3">
                    <label for="timeslot_end" class="form-label">Timeslot End</label>
                    <input type="time" class="form-control" id="timeslot_end" name="timeslot_end" required>
                </div>
                <div class="mb-3">
                    <label for="joining_date" class="form-label">Joining Date</label>
                    <input type="date" class="form-control" id="joining_date" name="joining_date" required>
                </div>
                <button type="submit" class="btn btn-primary">Join</button>
            </form>
        </div>
    </div>
</div>
@endsection 