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
        $students = StudentProfile::with(['user', 'seat'])->orderBy('payment_due_date', 'asc')->get();
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
            'timeslot_1_start' => 'required',
            'timeslot_1_end' => 'required',
            'timeslot_2_start' => 'nullable',
            'timeslot_2_end' => 'nullable',
            'timeslot_3_start' => 'nullable',
            'timeslot_3_end' => 'nullable',
            'reserve_seat' => 'nullable|boolean',
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
        $student->timeslot_1_start = $validated['timeslot_1_start'];
        $student->timeslot_1_end = $validated['timeslot_1_end'];
        $student->timeslot_2_start = $validated['timeslot_2_start'] ?? null;
        $student->timeslot_2_end = $validated['timeslot_2_end'] ?? null;
        $student->timeslot_3_start = $validated['timeslot_3_start'] ?? null;
        $student->timeslot_3_end = $validated['timeslot_3_end'] ?? null;
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
            $seat->is_reserved = $validated['reserve_seat'] ?? 0;
            $seat->save();
        }

        return redirect()->route('admin.students.index')->with('success', 'Student data updated successfully.');
    }
    public function destroy($id)
    {
        $student = StudentProfile::findOrFail($id);
        $student->delete();

        // Update seat status
        $seat = Seat::find($student->seat_id);
        $seat->status = 'vacant';
        $seat->assigned_to = null;
        $seat->is_reserved = 0;
        $seat->save();

        return redirect()->route('admin.students.index')->with('success', 'Student data deleted successfully.');
    }
} 