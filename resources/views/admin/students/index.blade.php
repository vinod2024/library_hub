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
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Profile Photo" class="border" style="width: 100px; height: 120px; object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:70px;height:70px;font-size:2rem;">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h5 class="mb-1">{{ ucwords($student->user->name ?? '-') }}</h5>
                        <ul class="list-unstyled mb-0 text-secondary">
                            <li><i class="bi bi-person-circle me-2"></i> {{ $student->register_no ?? '-' }}</li>
                            <li><i class="bi bi-telephone me-2"></i> {{ $student->mobile ?? '-' }}</li>
                            <li><i class="bi bi-envelope me-2"></i> {{ $student->user->email ?? '-' }}</li>
                        </ul>
                    </div>
                </div>
            </td>
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