<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\Seat;
use App\Models\Timesheet;
use Carbon\Carbon;

class TimesheetController extends Controller
{
    public function checkIn(Request $request)
    {
        $studentProfile = StudentProfile::where('user_id', auth()->id())->first();
        
        if (!$studentProfile) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found. Please join the library first.');
        }

        // Check if current time is within allowed timeslot
        $currentTime = Carbon::now();
        
        // Handle different time formats
        $timeslotStart = null;
        $timeslotEnd = null;
        
        if (is_string($studentProfile->timeslot_start)) {
            $timeslotStart = Carbon::createFromFormat('H:i', $studentProfile->timeslot_start);
        } else {
            $timeslotStart = $studentProfile->timeslot_start;
        }
        
        if (is_string($studentProfile->timeslot_end)) {
            $timeslotEnd = Carbon::createFromFormat('H:i', $studentProfile->timeslot_end);
        } else {
            $timeslotEnd = $studentProfile->timeslot_end;
        }
        
                // Check if current time is within timeslot using proper time comparison
        $currentHour = (int)$currentTime->format('H');
        $currentMinute = (int)$currentTime->format('i');
        $currentTimeMinutes = $currentHour * 60 + $currentMinute;
        
        $startHour = (int)$timeslotStart->format('H');
        $startMinute = (int)$timeslotStart->format('i');
        $startTimeMinutes = $startHour * 60 + $startMinute;
        
        $endHour = (int)$timeslotEnd->format('H');
        $endMinute = (int)$timeslotEnd->format('i');
        $endTimeMinutes = $endHour * 60 + $endMinute;
        
        $isWithinTimeslot = false;
        
        // Handle timeslot that spans across midnight (e.g., 23:31 to 10:41)
        if ($startTimeMinutes > $endTimeMinutes) {
            // Timeslot spans midnight: current time should be >= start OR <= end
            $isWithinTimeslot = ($currentTimeMinutes >= $startTimeMinutes || $currentTimeMinutes <= $endTimeMinutes);
        } else {
            // Normal timeslot: current time should be >= start AND <= end
            $isWithinTimeslot = ($currentTimeMinutes >= $startTimeMinutes && $currentTimeMinutes <= $endTimeMinutes);
        }

        /* $isWithinTimeslot = ($currentTimeMinutes >= $startTimeMinutes && $currentTimeMinutes <= $endTimeMinutes);
        var_dump($isWithinTimeslot);
        die(); */

        if (!$isWithinTimeslot) {
            return redirect()->route('student.dashboard')->with('error', 'Check-in is only allowed during your assigned timeslot: ' . 
                $timeslotStart->format('H:i') . ' - ' . $timeslotEnd->format('H:i'));
        }

        // Check if already checked in today
        $todayTimesheet = Timesheet::where('student_profile_id', $studentProfile->id)
            ->where('date', $currentTime->toDateString())
            ->first();

        /* if ($todayTimesheet && $todayTimesheet->check_in) {
            return redirect()->route('student.dashboard')->with('error', 'You have already checked in today.');
        } */

        // Get available seats
        $availableSeats = Seat::where('status', 'vacant')
            ->whereNull('assigned_to')
            ->where('is_reserved', 0)
            ->get();

        if ($availableSeats->isEmpty()) {
            return redirect()->route('student.dashboard')->with('error', 'No seats are currently available. Please try again later.');
        }

        // If seat selection is provided
        if ($request->has('seat_id') && $request->input('seat_id')) {
            $selectedSeat = Seat::find($request->seat_id);
            
            if (!$selectedSeat || $selectedSeat->status !== 'vacant' || $selectedSeat->assigned_to !== null) {
                return redirect()->route('student.dashboard')->with('error', 'Selected seat is not available.');
            }

            // Assign seat to student
            $selectedSeat->update([
                'status' => 'occupied',
                'assigned_to' => $studentProfile->id
            ]);

            // Update student profile with seat
            $studentProfile->update(['seat_id' => $selectedSeat->id]);

            // Create or update timesheet
            /* if (!$todayTimesheet) {
                $todayTimesheet = Timesheet::create([
                    'student_profile_id' => $studentProfile->id,
                    'date' => $currentTime->toDateString(),
                    'check_in' => $currentTime->toTimeString(),
                    'status' => 'pending'
                ]);
            } else {
                $todayTimesheet->update(['check_in' => $currentTime->toTimeString()]);
            } */

            $todayTimesheet = Timesheet::create([
                'student_profile_id' => $studentProfile->id,
                'date' => $currentTime->toDateString(),
                'check_in' => $currentTime->toTimeString(),
                'status' => 'pending'
            ]);

            return redirect()->route('student.dashboard')->with('success', 'Successfully checked in and assigned to seat ' . $selectedSeat->number);
        }

        // Show seat selection view
        return view('student.select-seat', compact('availableSeats', 'studentProfile'));
    }

    public function checkOut(Request $request)
    {
        $studentProfile = StudentProfile::where('user_id', auth()->id())->first();
        
        if (!$studentProfile) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        // Check if current time is within allowed timeslot
        $currentTime = Carbon::now();
        
        // Handle different time formats
        $timeslotStart = null;
        $timeslotEnd = null;
        
        if (is_string($studentProfile->timeslot_start)) {
            $timeslotStart = Carbon::createFromFormat('H:i', $studentProfile->timeslot_start);
        } else {
            $timeslotStart = $studentProfile->timeslot_start;
        }
        
        if (is_string($studentProfile->timeslot_end)) {
            $timeslotEnd = Carbon::createFromFormat('H:i', $studentProfile->timeslot_end);
        } else {
            $timeslotEnd = $studentProfile->timeslot_end;
        }
        
        // Check if current time is within timeslot using proper time comparison
        $currentHour = (int)$currentTime->format('H');
        $currentMinute = (int)$currentTime->format('i');
        $currentTimeMinutes = $currentHour * 60 + $currentMinute;
        
        $startHour = (int)$timeslotStart->format('H');
        $startMinute = (int)$timeslotStart->format('i');
        $startTimeMinutes = $startHour * 60 + $startMinute;
        
        $endHour = (int)$timeslotEnd->format('H');
        $endMinute = (int)$timeslotEnd->format('i');
        $endTimeMinutes = $endHour * 60 + $endMinute;
        
        $isWithinTimeslot = false;
        
        // Handle timeslot that spans across midnight (e.g., 23:31 to 10:41)
        if ($startTimeMinutes > $endTimeMinutes) {
            // Timeslot spans midnight: current time should be >= start OR <= end
            $isWithinTimeslot = ($currentTimeMinutes >= $startTimeMinutes || $currentTimeMinutes <= $endTimeMinutes);
        } else {
            // Normal timeslot: current time should be >= start AND <= end
            $isWithinTimeslot = ($currentTimeMinutes >= $startTimeMinutes && $currentTimeMinutes <= $endTimeMinutes);
        }
        
        /* if (!$isWithinTimeslot) {
            return redirect()->route('student.dashboard')->with('error', 'Check-out is only allowed during your assigned timeslot: ' . 
                $timeslotStart->format('H:i') . ' - ' . $timeslotEnd->format('H:i'));
        } */

        // Check if checked in today
        $todayTimesheet = Timesheet::where('student_profile_id', $studentProfile->id)
            ->where('date', $currentTime->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        if (!$todayTimesheet || !$todayTimesheet->check_in) {
            return redirect()->route('student.dashboard')->with('error', 'You must check in before checking out.');
        }

        if ($todayTimesheet->check_out) {
            return redirect()->route('student.dashboard')->with('error', 'You have already checked out today.');
        }

        // Update timesheet with check-out time
        $todayTimesheet->update([
            'check_out' => $currentTime->toTimeString(),
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

        return redirect()->route('student.dashboard')->with('success', 'Successfully checked out. Your seat has been freed.');
    }
} 