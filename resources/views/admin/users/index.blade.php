@extends('layouts.admin')
@section('content')
<h1>Users</h1>
<table class="min-w-full bg-white">
    <thead>
        <tr>
            <th class="py-2 px-4 border-b">ID</th>
            <th class="py-2 px-4 border-b">Name</th>
            <th class="py-2 px-4 border-b">Email</th>
            <th class="py-2 px-4 border-b">Role</th>
            <th class="py-2 px-4 border-b">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td class="py-2 px-4 border-b">{{ $user->id }}</td>
            <td class="py-2 px-4 border-b">{{ $user->name }}</td>
            <td class="py-2 px-4 border-b">{{ $user->email }}</td>
            <td class="py-2 px-4 border-b">{{ ucfirst($user->role) }}</td>
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