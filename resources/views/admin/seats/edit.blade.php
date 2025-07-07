@extends('layouts.admin')
@section('content')
<div class="container">
    <h1 class="mb-4">Edit Seat</h1>
    <form action="{{ route('admin.seats.update', $seat->id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="number" class="form-label">Seat Number</label>
            <input type="text" class="form-control" id="number" name="number" value="{{ old('number', $seat->number) }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="vacant" {{ old('status', $seat->status) == 'vacant' ? 'selected' : '' }}>Vacant</option>
                <option value="occupied" {{ old('status', $seat->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
            </select>
        </div>
        <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success btn-sm">Update Seat</button>
        <a href="{{ route('admin.seats.index') }}" class="btn btn-secondary btn-sm ms-2">Cancel</a>
        </div>
    </form>
</div>
@endsection 