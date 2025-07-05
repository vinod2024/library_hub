<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\JoinLibraryRequest;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Storage;

class LibraryJoinController extends Controller
{
    public function showForm()
    {
        return view('student.join');
    }

    public function submitForm(JoinLibraryRequest $request)
    {
        $validated = $request->validated();

        $photoPath = $request->file('photo')->store('photos', 'public');
        $idProofPath = $request->file('id_proof')->store('id_proofs', 'public');
 
        StudentProfile::create([
            'user_id' => auth()->id(),
            'mobile' => $validated['mobile'],
            'address' => $validated['address'],
            'photo' => $photoPath,
            'id_proof' => $idProofPath,
            'timeslot_start' => $validated['timeslot_start'],
            'timeslot_end' => $validated['timeslot_end'],
            'joining_date' => $validated['joining_date'],
        ]);

        return redirect()->route('student.dashboard')->with('success', 'You have successfully joined the library!');
    }
} 