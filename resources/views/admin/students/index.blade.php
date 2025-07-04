@extends('layouts.admin')
@section('content')
<h1>Students</h1>
<table class="min-w-full bg-white">
    <thead>
        <tr>
            <th class="py-2 px-4 border-b">ID</th>
            <th class="py-2 px-4 border-b">Name</th>
            <th class="py-2 px-4 border-b">Mobile</th>
            <th class="py-2 px-4 border-b">Seat</th>
            <th class="py-2 px-4 border-b">Timeslot</th>
            <th class="py-2 px-4 border-b">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <tr>
            <td class="py-2 px-4 border-b">{{ $student->id }}</td>
            <td class="py-2 px-4 border-b">{{ $student->user->name ?? 'N/A' }}</td>
            <td class="py-2 px-4 border-b">{{ $student->mobile }}</td>
            <td class="py-2 px-4 border-b">{{ $student->seat->number ?? 'Unassigned' }}</td>
            <td class="py-2 px-4 border-b">{{ $student->timeslot_start }} - {{ $student->timeslot_end }}</td>
            <td class="py-2 px-4 border-b">
                <!-- Actions: Edit/Delete buttons -->
                <a href="#" class="text-blue-600">Edit</a> |
                <a href="#" class="text-red-600">Delete</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection 