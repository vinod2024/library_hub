<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['number', 'status', 'assigned_to'];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class, 'assigned_to');
    }
}
