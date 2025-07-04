<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id', 'mobile', 'address', 'photo', 'id_proof', 'courses',
        'purpose', 'timeslot_start', 'timeslot_end', 'joining_date', 'seat_id'
    ];

    protected $casts = [
        'courses' => 'array',
        'joining_date' => 'date',
        'timeslot_start' => 'datetime:H:i',
        'timeslot_end' => 'datetime:H:i',
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
}
