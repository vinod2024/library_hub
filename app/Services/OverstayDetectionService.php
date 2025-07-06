<?php

namespace App\Services;

use App\Models\StudentProfile;
use App\Models\Timesheet;
use Carbon\Carbon;

class OverstayDetectionService
{
    public function getOverstayingStudents()
    {
        $currentTime = Carbon::now()->addMinutes(1);
        $today = $currentTime->toDateString();
        
        // Get all students who are currently checked in but haven't checked out
        $checkedInStudents = Timesheet::where('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->with(['studentProfile.user', 'studentProfile.seat'])
            ->get();
        
        $overstayingStudents = [];
        
        foreach ($checkedInStudents as $timesheet) {
            $studentProfile = $timesheet->studentProfile;
            
            if (!$studentProfile) {
                continue;
            }
            
            // Parse timeslot times - handle both time and datetime formats
            $timeslotStart = null;
            $timeslotEnd = null;
            
            if (is_string($studentProfile->timeslot_start)) {
                if (strlen($studentProfile->timeslot_start) <= 5) {
                    // Time format (H:i)
                    $timeslotStart = Carbon::createFromFormat('H:i', $studentProfile->timeslot_start);
                } else {
                    // Datetime format
                    $timeslotStart = Carbon::parse($studentProfile->timeslot_start);
                }
            } else {
                $timeslotStart = $studentProfile->timeslot_start;
            }
            
            if (is_string($studentProfile->timeslot_end)) {
                if (strlen($studentProfile->timeslot_end) <= 5) {
                    // Time format (H:i)
                    $timeslotEnd = Carbon::createFromFormat('H:i', $studentProfile->timeslot_end);
                } else {
                    // Datetime format
                    $timeslotEnd = Carbon::parse($studentProfile->timeslot_end);
                }
            } else {
                $timeslotEnd = $studentProfile->timeslot_end;
            }
            
            // Convert to minutes for comparison
            $currentMinutes = $currentTime->hour * 60 + $currentTime->minute;
            $startMinutes = $timeslotStart->hour * 60 + $timeslotStart->minute;
            $endMinutes = $timeslotEnd->hour * 60 + $timeslotEnd->minute;
            
            $isOverstaying = false;
            
            // Handle timeslot that spans across midnight
            if ($startMinutes > $endMinutes) {
                // Timeslot spans midnight: current time should be >= start OR <= end
                $isOverstaying = !($currentMinutes >= $startMinutes || $currentMinutes <= $endMinutes);
            } else {
                // Normal timeslot: current time should be >= start AND <= end
                $isOverstaying = !($currentMinutes >= $startMinutes && $currentMinutes <= $endMinutes);
            }
            
            if ($isOverstaying) {
                // Calculate overstay duration - handle both time and datetime formats
                $checkInTime = null;
                if (is_string($timesheet->check_in)) {
                    if (strlen($timesheet->check_in) <= 5) {
                        // Time format (H:i)
                        $checkInTime = Carbon::createFromFormat('H:i', $timesheet->check_in);
                    } else {
                        // Datetime format
                        $checkInTime = Carbon::parse($timesheet->check_in);
                    }
                } else {
                    $checkInTime = $timesheet->check_in;
                }
                $overstayMinutes = $currentTime->diffInMinutes($checkInTime);
                
                $overstayingStudents[] = [
                    'student_id' => $studentProfile->id,
                    'user_name' => $studentProfile->user->name,
                    'seat_number' => $studentProfile->seat ? $studentProfile->seat->number : 'N/A',
                    'timeslot_start' => Carbon::parse($studentProfile->timeslot_start)->format('h:i A'),
                    'timeslot_end' => Carbon::parse($studentProfile->timeslot_end)->format('h:i A'),
                    'check_in_time' => $timesheet->check_in,
                    'overstay_minutes' => $overstayMinutes,
                    'overstay_duration' => $this->formatDuration($overstayMinutes)
                ];
            }
        }
        
        return $overstayingStudents;
    }
    
    private function formatDuration($minutes)
    {
        $minutes = round($minutes);
        if ($minutes < 60) {
            return $minutes . ' minutes';
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($remainingMinutes == 0) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '');
        }
        
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $remainingMinutes . ' minute' . ($remainingMinutes > 1 ? 's' : '');
    }
} 