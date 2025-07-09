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
            'timeslot_1_start' => $validated['timeslot_1_start'],
            'timeslot_1_end' => $validated['timeslot_1_end'],
            'timeslot_2_start' => $validated['timeslot_2_start'],
            'timeslot_2_end' => $validated['timeslot_2_end'],
            'timeslot_3_start' => $validated['timeslot_3_start'],
            'timeslot_3_end' => $validated['timeslot_3_end'],
            'joining_date' => $validated['joining_date'],
        ]);

        return redirect()->route('student.dashboard')->with('success', 'You have successfully joined the library!');
    }
} 