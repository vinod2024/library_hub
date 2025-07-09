@extends('layouts.admin')
@section('content')
<div class="container">
    <h1 class="mb-4">Edit User</h1>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Student</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="enabled" class="form-label">Status</label>
            <select class="form-select" id="enabled" name="enabled" required>
                <option value="1" {{ old('enabled', $user->enabled) ? 'selected' : '' }}>Enabled</option>
                <option value="0" {{ !old('enabled', $user->enabled) ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </div>
    </form>
</div>
@endsection 