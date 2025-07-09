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

        // Check if current time is within any allowed timeslot
        $currentTime = Carbon::now();
        $currentTimeMinutes = (int)$currentTime->format('H') * 60 + (int)$currentTime->format('i');
        $isWithinTimeslot = false;
        $timeslotMessages = [];
        for ($i = 1; $i <= 3; $i++) {
            $start = $studentProfile->{"timeslot_{$i}_start"};
            $end = $studentProfile->{"timeslot_{$i}_end"};
            if (!$start || !$end) continue; // skip incomplete timeslot
            $startTime = is_string($start) ? Carbon::createFromFormat('H:i', $start) : $start;
            $endTime = is_string($end) ? Carbon::createFromFormat('H:i', $end) : $end;
            $startMinutes = (int)$startTime->format('H') * 60 + (int)$startTime->format('i');
            $endMinutes = (int)$endTime->format('H') * 60 + (int)$endTime->format('i');
            $timeslotMessages[] = $startTime->format('H:i') . ' - ' . $endTime->format('H:i');

            if ($currentTimeMinutes >= $startMinutes && $currentTimeMinutes <= $endMinutes) {
                $isWithinTimeslot = true;
                $studentProfile->update(['timeslot_start' => $startTime->format('H:i'), 'timeslot_end' => $endTime->format('H:i')]);
                // return $studentProfile;
                break;
            }
           /*  if ($startMinutes > $endMinutes) {
                return $startMinutes;
                // Timeslot spans midnight
                if ($currentTimeMinutes >= $startMinutes || $currentTimeMinutes <= $endMinutes) {
                    $isWithinTimeslot = true;
                    break;
                }
            } else {
                return 'else'.$startMinutes;
                if ($currentTimeMinutes >= $startMinutes && $currentTimeMinutes <= $endMinutes) {
                    $isWithinTimeslot = true;
                    break;
                }
            } */
        }
        if (!$isWithinTimeslot) {
            return redirect()->route('student.dashboard')->with('error', 'Check-in is only allowed during your assigned timeslots: ' . implode(' | ', $timeslotMessages));
        }

        // Check if already checked in today
        $todayTimesheet = Timesheet::where('student_profile_id', $studentProfile->id)
            ->where('date', $currentTime->toDateString())
            ->first();

        /* if ($todayTimesheet && $todayTimesheet->check_in) {
            return redirect()->route('student.dashboard')->with('error', 'You have already checked in today.');
        } */

        // Get available seats
        if(!empty($studentProfile->seat_id)){
            $availableSeats = Seat::where('is_reserved', 1)
                ->where('assigned_to', $studentProfile->id)
                ->get();
            // dd($availableSeats);
        }else{
            $availableSeats = Seat::where('status', 'vacant')
                ->whereNull('assigned_to')
                ->where('is_reserved', 0)
                ->get();
        }    


        if ($availableSeats->isEmpty()) {
            return redirect()->route('student.dashboard')->with('error', 'No seats are currently available. Please try again later.');
        }

        // If seat selection is provided
        if ($request->has('seat_id') && $request->input('seat_id')) {

            $selectedSeat = Seat::find($request->seat_id);
            

            if(empty($studentProfile->seat_id)){
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
            }
            
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
        $currentTimeMinutes = (int)$currentTime->format('H') * 60 + (int)$currentTime->format('i');
        $isWithinTimeslot = false;
        $timeslotMessages = [];
        /* for ($i = 1; $i <= 3; $i++) {
            $start = $studentProfile->{"timeslot_{$i}_start"};
            $end = $studentProfile->{"timeslot_{$i}_end"};
            if (!$start || !$end) continue; // skip incomplete timeslot
            $startTime = is_string($start) ? Carbon::createFromFormat('H:i', $start) : $start;
            $endTime = is_string($end) ? Carbon::createFromFormat('H:i', $end) : $end;
            $startMinutes = (int)$startTime->format('H') * 60 + (int)$startTime->format('i');
            $endMinutes = (int)$endTime->format('H') * 60 + (int)$endTime->format('i');
            $timeslotMessages[] = $startTime->format('H:i') . ' - ' . $endTime->format('H:i');
            if ($startMinutes > $endMinutes) {
                // Timeslot spans midnight: current time should be >= start OR <= end
                $isWithinTimeslot = ($currentTimeMinutes >= $startMinutes || $currentTimeMinutes <= $endMinutes);
            } else {
                // Normal timeslot: current time should be >= start AND <= end
                $isWithinTimeslot = ($currentTimeMinutes >= $startMinutes && $currentTimeMinutes <= $endMinutes);
            }
        }
        if (!$isWithinTimeslot) {
            return redirect()->route('student.dashboard')->with('error', 'Check-out is only allowed during your assigned timeslots: ' . implode(' | ', $timeslotMessages));
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
            if($seat->is_reserved == 0){
                if ($seat) {
                    $seat->update([
                        'status' => 'vacant',
                        'assigned_to' => null
                    ]);
                }
                
                // Remove seat assignment from student profile
                $studentProfile->update(['seat_id' => null]);
            }
        }

        return redirect()->route('student.dashboard')->with('success', 'Successfully checked out. Your seat has been freed.');
    }
} 