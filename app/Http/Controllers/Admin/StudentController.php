<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\Seat;

class StudentController extends Controller
{
    public function index()
    {
        $students = StudentProfile::with(['user', 'seat'])->get();
        return view('admin.students.index', compact('students'));
    }
    public function create() {}
    public function store(Request $request) {
        // Implement student creation logic here if needed in the future
    }
    public function show($id) {}
    public function edit($id)
    {
        $student = StudentProfile::with(['user', 'seat'])->findOrFail($id);
        // Optionally, load all seats for selection
        $seats = \App\Models\Seat::all();
        return view('admin.students.edit', compact('student', 'seats'));
    }
    public function update(Request $request, $id)
    {
        $student = StudentProfile::with('user')->findOrFail($id);
        $previousSeatId = $student->seat_id;
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'seat_id' => 'nullable|exists:seats,id',
            'register_no' => 'required|string|max:255',
            'timeslot_start' => 'required',
            'timeslot_end' => 'required',
        ]);

        // Update user name if available
        if ($student->user) {
            $student->user->name = $validated['name'];
            $student->user->save();
        }

        // Update student profile fields
        $student->mobile = $validated['mobile'];
        $student->seat_id = $validated['seat_id'] ?? null;
        $student->register_no = $validated['register_no'];
        $student->timeslot_start = $validated['timeslot_start'];
        $student->timeslot_end = $validated['timeslot_end'];
        $student->save();

        if($previousSeatId){
            $seat = Seat::find($previousSeatId);
            $seat->status = 'vacant';
            $seat->assigned_to = null;
            $seat->is_reserved = 0;
            $seat->save();
        }

        // Update seat status
        if ($validated['seat_id']) {
            // $student = StudentProfile::find($id);
            
            // Update seat status
            $seat = Seat::find($validated['seat_id']);
            $seat->status = 'occupied';
            $seat->assigned_to = $id;
            $seat->is_reserved = 1;
            $seat->save();
        }

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }
    public function destroy($id)
    {
        $student = StudentProfile::findOrFail($id);
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
} 