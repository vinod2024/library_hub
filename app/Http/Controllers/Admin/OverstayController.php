<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OverstayDetectionService;
use App\Models\StudentProfile;
use App\Models\Timesheet;
use App\Models\Seat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OverstayController extends Controller
{
    protected $overstayService;
    
    public function __construct(OverstayDetectionService $overstayService)
    {
        $this->overstayService = $overstayService;
    }
    
    public function getOverstayingStudents()
    {
        $overstayingStudents = $this->overstayService->getOverstayingStudents();
        
        return response()->json([
            'success' => true,
            'data' => $overstayingStudents,
            'count' => count($overstayingStudents),
            'timestamp' => now()->toISOString()
        ]);
    }
    
    public function forceCheckOut(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_profiles,id'
        ]);
        
        try {
            $studentProfile = StudentProfile::findOrFail($request->student_id);
            
            // Find today's timesheet
            $todayTimesheet = Timesheet::where('student_profile_id', $studentProfile->id)
                ->where('date', Carbon::now()->toDateString())
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->orderBy('id', 'desc')
                ->first();
            
            if (!$todayTimesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active check-in found for this student.'
                ], 400);
            }
            
            // Update timesheet with check-out time
            $todayTimesheet->update([
                'check_out' => Carbon::now()->toTimeString(),
                'status' => 'completed'
            ]);
            
            // Free up the seat if assigned
            if ($studentProfile->seat_id) {
                $seat = Seat::find($studentProfile->seat_id);
                if ($seat) {
                    $seat->update([
                        'status' => 'vacant',
                        'assigned_to' => null
                    ]);
                }
                
                // Remove seat assignment from student profile
                $studentProfile->update(['seat_id' => null]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Student has been successfully checked out.',
                'student_name' => $studentProfile->user->name,
                'check_out_time' => Carbon::now()->format('H:i:s')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing check-out: ' . $e->getMessage()
            ], 500);
        }
    }
} 