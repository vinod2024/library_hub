@extends('layouts.admin')
@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<h1>Seats</h1>
<a href="{{ route('admin.seats.create') }}" class="btn btn-success mb-3">Add Seat</a>
<table class="min-w-full bg-white">
    <thead>
        <tr>
            <th class="py-2 px-4 border-b">ID</th>
            <th class="py-2 px-4 border-b">Number</th>
            <th class="py-2 px-4 border-b">Status</th>
            <th class="py-2 px-4 border-b">Assigned To</th>
            <th class="py-2 px-4 border-b">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($seats as $seat)
        <tr>
            <td class="py-2 px-4 border-b">{{ $seat->id }}</td>
            <td class="py-2 px-4 border-b">{{ $seat->number }}</td>
            <td class="py-2 px-4 border-b">{{ ucfirst($seat->status) }}</td>
            <td class="py-2 px-4 border-b">
                @if($seat->studentProfile)
                    {{ $seat->studentProfile->user->name ?? 'N/A' }}
                @else
                    Vacant
                @endif
            </td>
            <td class="py-2 px-4 border-b">
                <!-- Actions: Edit/Delete buttons -->
                <a href="{{ route('admin.seats.edit', $seat->id) }}" class="btn btn-sm btn-primary">Edit</a>
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
@endsection 