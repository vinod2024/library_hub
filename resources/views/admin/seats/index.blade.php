@extends('layouts.admin')
@section('content')


<h1 class="mb-4">Seat List</h1>
<a href="{{ route('admin.seats.create') }}" class="btn btn-success mb-3">Add Seat</a>


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
            <th scope="col">Number</th>
            <th scope="col">Status</th>
            <th scope="col">Assigned To</th>
            <th scope="col">Sort By</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($seats as $seat)
        <tr>
            <td>{{ $seat->id }}</td>
            <td>{{ $seat->number }}</td>
            <td>{{ ucfirst($seat->status) }}</td>
            <td>
                @if($seat->studentProfile)
                    {{ $seat->studentProfile->user->name ?? 'N/A' }}
                @else
                    Vacant
                @endif
            </td>
            <td>{{ $seat->sort_by }}</td>
            <td>
                <a href="{{ route('admin.seats.edit', $seat->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                <form action="{{ route('admin.seats.destroy', $seat->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this seat?');">
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