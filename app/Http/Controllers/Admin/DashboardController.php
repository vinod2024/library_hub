<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Seat;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        // $totalStudents = User::where('role', 'student')->count();

        $totalStudents = StudentProfile::count();
        $totalSeats = Seat::count();
        $vacantSeats = Seat::where('status', 'vacant')->where('is_reserved', '0')->count();
        $vacantSeatsList = Seat::where('status', 'vacant')->where('is_reserved', 0)->orderBy('sort_by', 'asc')->get();
        // Group by first letter
        $groupedVacantSeats = $vacantSeatsList->groupBy(function($seat) {
            return strtoupper(substr($seat->number, 0, 1));
        });
        // Reserved seats block
        $reservedSeatsList = Seat::where('is_reserved', 1)
            ->with('studentProfile')
            ->orderBy('sort_by', 'asc')
            ->get();
        // Add a flag for checked-in status
        $reservedSeatsList->map(function($seat) {
            $seat->is_checked_in = $seat->studentProfile && $seat->studentProfile->isCurrentlyCheckedIn();
            return $seat;
        });
        $groupedReservedSeats = $reservedSeatsList->groupBy(function($seat) {
            return strtoupper(substr($seat->number, 0, 1));
        });
        return view('admin.dashboard', compact('totalUsers', 'totalStudents', 'totalSeats', 'vacantSeats', 'groupedVacantSeats', 'groupedReservedSeats'));
    }

    public function getVacantSeats()
    {
        $vacantSeats = Seat::where('status', 'vacant')->where('is_reserved', 0)->count();
        $vacantSeatsList = Seat::where('status', 'vacant')->where('is_reserved', 0)->orderBy('sort_by', 'asc')->get();
        // Group by first letter
        $groupedVacantSeats = $vacantSeatsList->groupBy(function($seat) {
            return strtoupper(substr($seat->number, 0, 1));
        });
        return response()->json([
            'success' => true,
            'data' => [
                'vacantSeats' => $vacantSeats,
                'groupedVacantSeats' => $groupedVacantSeats
            ],
            'timestamp' => now()->toISOString()
        ]);
    }
} 