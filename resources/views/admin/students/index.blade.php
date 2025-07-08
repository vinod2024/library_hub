@extends('layouts.admin')
@section('content')
<h1 class="mb-4">Students</h1>
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
            <th scope="col">Photo</th>
            <!-- <th scope="col">Name</th> -->
            <!-- <th scope="col">Mobile</th> -->
            <th scope="col">Seat</th>
            <th scope="col">Timeslot</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $key => $student)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>
                <img src="{{ asset('storage/'.$student->photo) }}" alt="Photo" class="img-fluid" style="width: 75px; height: 85px;">
                <br>
                <span class="text-muted">{{ $student->register_no ?? 'N/A' }}</span>
                <br>
                <span class="text-muted">{{ $student->user->name ?? 'N/A' }}</span>
                <br>
                <span class="text-muted">{{ $student->user->mobile ?? 'N/A' }}</span>
            </td>
            <!-- <td>{{ $student->user->name ?? 'N/A' }}</td>
            <td>{{ $student->mobile }}</td> -->
            <td>{{ $student->seat->number ?? 'Unassigned' }}</td>
            <td>{{ $student->timeslot_start }} - {{ $student->timeslot_end }}</td>
            <td>
                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this student?');">
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