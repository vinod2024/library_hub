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
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
} 