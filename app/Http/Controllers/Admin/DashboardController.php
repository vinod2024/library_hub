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
        $totalStudents = User::where('role', 'student')->count();
        $totalSeats = Seat::count();
        $vacantSeats = Seat::where('status', 'vacant')->count();
        $vacantSeatsList = Seat::where('status', 'vacant')->orderBy('number', 'asc')->get();
        return view('admin.dashboard', compact('totalUsers', 'totalStudents', 'totalSeats', 'vacantSeats', 'vacantSeatsList'));
    }

    public function getVacantSeats()
    {
        $vacantSeats = Seat::where('status', 'vacant')->count();
        $vacantSeatsList = Seat::where('status', 'vacant')->orderBy('number', 'asc')->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'vacantSeats' => $vacantSeats,
                'vacantSeatsList' => $vacantSeatsList
            ],
            'timestamp' => now()->toISOString()
        ]);
    }
} 