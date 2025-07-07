<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seat;

class SeatController extends Controller
{
    public function index()
    {
        $seats = Seat::with('studentProfile')->get();
        return view('admin.seats.index', compact('seats'));
    }
    public function create() {
        return view('admin.seats.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|unique:seats,number',
            'status' => 'required|in:vacant,occupied',
        ]);
        \App\Models\Seat::create($validated);
        return redirect()->route('admin.seats.index')->with('success', 'Seat added successfully.');
    }
    public function show($id) {}
    public function edit($id)
    {
        $seat = Seat::findOrFail($id);
        return view('admin.seats.edit', compact('seat'));
    }
    public function update(Request $request, $id)
    {
        $seat = Seat::findOrFail($id);
        $validated = $request->validate([
            'number' => 'required|string|unique:seats,number,' . $seat->id,
            'status' => 'required|in:vacant,occupied',
        ]);
        $seat->update($validated);
        return redirect()->route('admin.seats.index')->with('success', 'Seat updated successfully.');
    }
    public function destroy($id)
    {
        $seat = Seat::findOrFail($id);
        $seat->delete();
        return redirect()->route('admin.seats.index')->with('success', 'Seat deleted successfully.');
    }
} 