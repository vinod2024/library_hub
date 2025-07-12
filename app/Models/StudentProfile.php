<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id', 'mobile', 'address', 'photo', 'id_proof', 'courses',
        'purpose', 'timeslot_start', 'timeslot_end', 'joining_date', 'seat_id', 'register_no',
        'timeslot_1_start', 'timeslot_1_end',
        'timeslot_2_start', 'timeslot_2_end',
        'timeslot_3_start', 'timeslot_3_end',
    ];

    protected $casts = [
        'courses' => 'array',
        'joining_date' => 'date',
        'timeslot_start' => 'datetime:H:i',
        'timeslot_end' => 'datetime:H:i',
        'timeslot_1_start' => 'datetime:H:i',
        'timeslot_1_end' => 'datetime:H:i',
        'timeslot_2_start' => 'datetime:H:i',
        'timeslot_2_end' => 'datetime:H:i',
        'timeslot_3_start' => 'datetime:H:i',
        'timeslot_3_end' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function isCurrentlyCheckedIn()
    {
        $today = now()->toDateString();
        $timesheet = $this->timesheets()
            ->where('date', $today)
            ->orderByDesc('id')
            ->first();
        return $timesheet && $timesheet->check_in && !$timesheet->check_out;
    }
}
