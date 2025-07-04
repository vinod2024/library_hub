<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Storage;

class LibraryJoinController extends Controller
{
    public function showForm()
    {
        return view('student.join');
    }

    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'id_proof' => 'nullable|file|max:2048',
            // 'courses' => 'required|array',
            // 'purpose' => 'required|string|max:255',
            'timeslot_start' => 'required',
            'timeslot_end' => 'required',
            'joining_date' => 'required|date',
        ]);

        $photoPath = $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null;
        $idProofPath = $request->file('id_proof') ? $request->file('id_proof')->store('id_proofs', 'public') : null;
 
        StudentProfile::create([
            'user_id' => auth()->id(),
            'mobile' => $validated['mobile'],
            'address' => $validated['address'],
            'photo' => $photoPath,
            'id_proof' => $idProofPath,
            // 'courses' => $validated['courses'],
            // 'purpose' => $validated['purpose'],
            'timeslot_start' => $validated['timeslot_start'],
            'timeslot_end' => $validated['timeslot_end'],
            'joining_date' => $validated['joining_date'],
        ]);

        return redirect()->route('student.dashboard')->with('success', 'You have successfully joined the library!');
    }
} 