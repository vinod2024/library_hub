<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'student_profile_id', 'date', 'check_in', 'check_out', 'status'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}
