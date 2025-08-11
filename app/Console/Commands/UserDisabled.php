<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserDisabled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-disabled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Disabled';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $user_disabled_time = config('app.user_disabled_time');
            $disabledDate = \Carbon\Carbon::now()->subDays($user_disabled_time)->format('Y-m-d');

            $userIds = DB::table('student_profiles')
                ->where('payment_due_date', '<=', $disabledDate)
                ->pluck('user_id')->toArray();

            DB::table('users')
                ->whereIn('id', $userIds)
                ->update(['enabled' => 0]);
                
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Disabled: ' . $e->getMessage());
        }
    }
}
