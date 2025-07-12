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
            <th scope="col">Student Details</th>
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
                            <li><i class="bi bi-person-vcard me-2"></i>
                                <a href="{{ asset('storage/' . $student->id_proof) }}" target="_blank">
                                    ID Proof
                                </a> 
                            </li>
                           

                        </ul>
                    </div>
                </div>
            </td>
            <td>
                @if($student->seat)
                    {{ $student->seat->number }}
                    @if($student->seat->is_reserved)
                        <i class="bi bi-lock-fill text-warning ms-1" title="Reserved Seat"></i>
                    @endif
                @else
                    --
                @endif
            </td>
            <td>
                Slot 1: {{ Carbon\Carbon::parse($student->timeslot_1_start)->format('h:i A') }} - {{ Carbon\Carbon::parse($student->timeslot_1_end)->format('h:i A') }}<br>
                @if($student->timeslot_2_start)
                Slot 2: {{ Carbon\Carbon::parse($student->timeslot_2_start)->format('h:i A') }} - {{ Carbon\Carbon::parse($student->timeslot_2_end)->format('h:i A') }}<br>
                @endif
                @if($student->timeslot_3_start)
                Slot 3: {{ Carbon\Carbon::parse($student->timeslot_3_start)->format('h:i A') }} - {{ Carbon\Carbon::parse($student->timeslot_3_end)->format('h:i A') }}
                @endif
            </td>
            <td>
                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" >Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection 