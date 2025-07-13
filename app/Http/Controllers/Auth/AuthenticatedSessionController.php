<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\StudentProfile;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } elseif ($user->hasRole('student')) {
            // Check payment_due_date and set session flag if due
            $studentProfile = StudentProfile::where('user_id', $user->id)->first();
            if ($studentProfile && $studentProfile->payment_due_date) {
                $today = \Carbon\Carbon::today();
                $dueDate = $studentProfile->payment_due_date->copy()->startOfDay();
            
                // 1. If payment_due_date is before today (in the past)
                // 2. Or if it's today or tomorrow (less than next 2 days)
                if ($dueDate->lt($today) || $dueDate->between($today, $today->copy()->addDay())) {
                    $request->session()->put('show_payment_due_popup', true);
                }
            }
            
            return redirect()->intended(route('student.dashboard', absolute: false));
        }

        // Default fallback
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
