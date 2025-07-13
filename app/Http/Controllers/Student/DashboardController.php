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

        if (!$studentProfile) {
            return redirect()->route('student.join.form');
        }

        // Show popup only if session flag is set (after login)
        $showPaymentDuePopup = false;
        if (session('show_payment_due_popup')) {
            $showPaymentDuePopup = true;
            session()->forget('show_payment_due_popup');
        }

        return view('student.dashboard', compact('studentProfile', 'showPaymentDuePopup'));
    }

    public function profile()
    {
        return view('student.profile');
    }
} 