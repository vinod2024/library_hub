@extends('layouts.admin')
@section('content')
<div class="container">
    <h1 class="mb-4">Edit Student</h1>
    <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $student->user->name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="mobile" class="form-label">Mobile</label>
            <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile', $student->mobile) }}" required>
        </div>
        <div class="mb-3">
            <label for="seat_id" class="form-label">Seat</label>
            <select class="form-select" id="seat_id" name="seat_id">
                <option value="">Unassigned</option>
                @foreach($seats as $seat)
                    <option 
                        value="{{ $seat->id }}" 
                        {{ $student->seat && $student->seat->id == $seat->id ? 'selected' : '' }}
                        @if($seat->is_reserved) style="background-color: #ffe5e5; color: #d9534f;" @endif
                    >
                        {{ $seat->number }}@if($seat->is_reserved) (Reserved)@endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="register_no" class="form-label">Register No</label>
            <input type="text" class="form-control" id="register_no" name="register_no" value="{{ old('register_no', $student->register_no) }}" required>
        </div>
        <div class="mb-3 row">
            <div class="col-md-6">
                <label for="timeslot_1_start" class="form-label">Timeslot 1 Start <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="timeslot_1_start" name="timeslot_1_start" value="{{ old('timeslot_1_start', $student->timeslot_1_start ? (is_string($student->timeslot_1_start) ? $student->timeslot_1_start : $student->timeslot_1_start->format('H:i')) : '') }}" required>
            </div>
            <div class="col-md-6">
                <label for="timeslot_1_end" class="form-label">Timeslot 1 End <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="timeslot_1_end" name="timeslot_1_end" value="{{ old('timeslot_1_end', $student->timeslot_1_end ? (is_string($student->timeslot_1_end) ? $student->timeslot_1_end : $student->timeslot_1_end->format('H:i')) : '') }}" required>
            </div>
            <div class="col-md-6">
                <label for="timeslot_2_start" class="form-label">Timeslot 2 Start</label>
                <input type="time" class="form-control" id="timeslot_2_start" name="timeslot_2_start" value="{{ old('timeslot_2_start', $student->timeslot_2_start ? (is_string($student->timeslot_2_start) ? $student->timeslot_2_start : $student->timeslot_2_start->format('H:i')) : '') }}">
            </div>
            <div class="col-md-6">
                <label for="timeslot_2_end" class="form-label">Timeslot 2 End</label>
                <input type="time" class="form-control" id="timeslot_2_end" name="timeslot_2_end" value="{{ old('timeslot_2_end', $student->timeslot_2_end ? (is_string($student->timeslot_2_end) ? $student->timeslot_2_end : $student->timeslot_2_end->format('H:i')) : '') }}">
            </div>
            <div class="col-md-6">
                <label for="timeslot_3_start" class="form-label">Timeslot 3 Start</label>
                <input type="time" class="form-control" id="timeslot_3_start" name="timeslot_3_start" value="{{ old('timeslot_3_start', $student->timeslot_3_start ? (is_string($student->timeslot_3_start) ? $student->timeslot_3_start : $student->timeslot_3_start->format('H:i')) : '') }}">
            </div>
            <div class="col-md-6">
                <label for="timeslot_3_end" class="form-label">Timeslot 3 End</label>
                <input type="time" class="form-control" id="timeslot_3_end" name="timeslot_3_end" value="{{ old('timeslot_3_end', $student->timeslot_3_end ? (is_string($student->timeslot_3_end) ? $student->timeslot_3_end : $student->timeslot_3_end->format('H:i')) : '') }}">
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success btn-sm">Update Student</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm ms-2">Cancel</a>
        </div>
    </form>
</div>
@endsection 