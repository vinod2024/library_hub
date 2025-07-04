@extends('layouts.admin')
@section('content')
<h1>Seats</h1>
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
                <a href="#" class="text-blue-600">Edit</a> |
                <a href="#" class="text-red-600">Delete</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection 