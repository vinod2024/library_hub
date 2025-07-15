<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['number', 'status', 'assigned_to', 'sort_by'];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class, 'assigned_to', 'id');
    }
}
