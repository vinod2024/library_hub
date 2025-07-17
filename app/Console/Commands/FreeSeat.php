<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

         // insert id into cron_logs
         $cron_log_id = DB::table('cron_log')->insertGetId([
            'status' => '0',
        ]);

        try {

            DB::beginTransaction();
            // free seat
            DB::table('seats')
                ->where('is_reserved',0)
                // ->where('status', 'occupied')
                // ->orWhereNotNull('assigned_to')
                ->update(['status' => 'vacant', 'assigned_to' => null]);

            // update checkout in timesheet
            DB::table('timesheets')
                ->where('status', 'pending')
                // ->orWhereNotNull('check_out')
                ->update(['status' => 'completed', 'check_out' => now()]);

            // update student profile.
            DB::table('student_profiles')
                ->join('seats', 'student_profiles.seat_id', '=', 'seats.id')
                // ->whereNotNull('seat_id')
                ->where('seats.is_reserved',0)
                ->update(['student_profiles.seat_id' => null]);

            DB::commit();
            DB::table('cron_log')->where('id', $cron_log_id)->update(['status' => '1']);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error('Error: ' . $th->getMessage());
            DB::table('cron_log')->where('id', $cron_log_id)->update(['status' => '2']);
        }   

        // $this->info('Seat freed successfully');
    }
}
