<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'enabled' => 'required|boolean',
        ]);
        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
    public function enable($id)
    {
        $user = User::findOrFail($id);
        $user->enabled = true;
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User enabled successfully.');
    }
    public function disable($id)
    {
        $user = User::findOrFail($id);
        $user->enabled = false;
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User disabled successfully.');
    }
} 