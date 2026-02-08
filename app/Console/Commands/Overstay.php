<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Overstay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overstay:detect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect and handle overstay of library seats';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            
            // Get all students who are currently checked in but haven't checked out
            $checkedInStudents = DB::table('timesheets')
                ->join('student_profiles', 'timesheets.student_profile_id', '=', 'student_profiles.id')
                ->where('date', now()->toDateString())
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->where('student_profiles.timeslot_end', '<', now())
                ->select('timesheets.id as timesheet_id', 'student_profiles.id as student_profile_id', 'student_profiles.seat_id')
                ->get();
            
            
            $overstayingStudents = [];
            // print_r($checkedInStudents);exit;
            // all overstaying students seat free and update timesheet and student profile.
            foreach ($checkedInStudents as $timesheet) {
                // free seat
                DB::table('seats')
                    ->where('id', $timesheet->seat_id)
                    ->update(['status' => 'vacant', 'assigned_to' => null]);

                // update checkout in timesheet
                DB::table('timesheets')
                    ->where('id', $timesheet->timesheet_id)
                    ->update(['status' => 'completed', 'check_out' => now()]);

                // update student profile.
                DB::table('student_profiles')
                    ->where('id', $timesheet->student_profile_id)
                    ->update(['seat_id' => null]);
            }
           

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error detecting overstay: ' . $e->getMessage());
        }

        // $this->info('Seat freed successfully');
    }
}
