<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\StudentPayment;
use Illuminate\Support\Facades\Validator;

class StudentPaymentController extends Controller
{
    // Show the payment form in a modal (AJAX)
    public function showForm($studentId)
    {
        $student = StudentProfile::with('user')->findOrFail($studentId);
        $payment = StudentPayment::where('student_id', $studentId)->orderByDesc('created_at')->first();
        return view('admin.students.payment-form', compact('student', 'payment'))->render();
    }

    // Store or update the payment record (AJAX)
    public function storeOrUpdate(Request $request, $studentId)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:Cash,Online',
            'amount' => 'required|numeric|min:0',
            'payment_due_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = StudentPayment::updateOrCreate(
            ['student_id' => $studentId],
            [
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'payment_due_date' => $request->payment_due_date,
            ]
        );

        // Update payment_due_date in student_profiles
        $student = StudentProfile::findOrFail($studentId);
        $student->payment_due_date = $request->payment_due_date;
        $student->save();

        return response()->json(['success' => true, 'message' => 'Payment record saved successfully.']);
    }
}
