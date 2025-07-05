<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;

class DashboardController extends Controller
{
    public function index()
    {
        $studentProfile = StudentProfile::with(['user', 'seat'])
            ->where('user_id', auth()->id())
            ->first();

        return view('student.dashboard', compact('studentProfile'));
    }

    public function profile()
    {
        return view('student.profile');
    }
} 