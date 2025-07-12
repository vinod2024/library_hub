<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\StudentProfile;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'payment_method',
        'amount',
        'payment_due_date',
    ];

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }
}
