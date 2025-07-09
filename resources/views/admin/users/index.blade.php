@extends('layouts.admin')
@section('content')
<h1 class="mb-4">Users</h1>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered align-middle bg-white">
    <thead class="table-primary">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>
                @if($user->enabled)
                    <span class="badge bg-success">Enabled</span>
                @else
                    <span class="badge bg-secondary">Disabled</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                @if($user->enabled)
                    <form action="{{ route('admin.users.disable', ['user' => $user->id]) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning me-1">Disable</button>
                    </form>
                @else
                    <form action="{{ route('admin.users.enable', ['user' => $user->id]) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success me-1">Enable</button>
                    </form>
                @endif
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection 