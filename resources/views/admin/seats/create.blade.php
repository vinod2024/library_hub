@extends('layouts.admin')
@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-3">Add Seat</h2>
</div>
<form method="POST" action="{{ route('admin.seats.store') }}">
    @csrf
    <div class="mb-3">
        <label for="number" class="form-label">Seat Number</label>
        <input type="text" class="form-control" id="number" name="number" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status" name="status" required>
            <option value="vacant">Vacant</option>
            <option value="occupied">Occupied</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Add Seat</button>
    <a href="{{ route('admin.seats.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection 