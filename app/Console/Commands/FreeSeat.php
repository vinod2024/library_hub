<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FreeSeat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'free:seat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            // free seat
            DB::table('seats')
                ->where('status', 'occupied')
                ->orWhereNotNull('assigned_to')
                ->update(['status' => 'vacant', 'assigned_to' => null]);

            // update checkout in timesheet
            DB::table('timesheets')
                ->where('status', 'pending')
                ->orWhereNotNull('check_out')
                ->update(['status' => 'completed', 'check_out' => now()]);

            // update student profile.
            DB::table('student_profiles')
                ->whereNotNull('seat_id')
                ->update(['seat_id' => null]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error('Error: ' . $th->getMessage());
        }   

        // $this->info('Seat freed successfully');
    }
}
