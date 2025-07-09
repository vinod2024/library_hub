<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class JoinLibraryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'student';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mobile' => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'address' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:10500|dimensions:max_width=2000,max_height=2000',
            'id_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10500',
            'timeslot_1_start' => 'required|date_format:H:i',
            'timeslot_1_end' => 'required|date_format:H:i|after:timeslot_1_start',
            /* 'timeslot_2_start' => 'date_format:H:i',
            'timeslot_2_end' => 'date_format:H:i|after:timeslot_2_start',
            'timeslot_3_start' => 'date_format:H:i',
            'timeslot_3_end' => 'date_format:H:i|after:timeslot_3_start', */
            'joining_date' => 'required|date',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'mobile.required' => 'Mobile number is required.',
            'mobile.regex' => 'Please enter a valid mobile number format.',
            'mobile.max' => 'Mobile number must not exceed 20 characters.',
            
            'address.required' => 'Address is required.',
            'address.max' => 'Address must not exceed 255 characters.',
            
            'photo.required' => 'A photo is required for library membership.',
            'photo.image' => 'The photo must be a valid image file.',
            'photo.mimes' => 'The photo must be a JPEG, PNG, or JPG file.',
            'photo.max' => 'The photo size must not exceed 4.5MB.',
            'photo.dimensions' => 'The photo dimensions must be less than  2000x2000 pixels.',
            
            'id_proof.required' => 'An ID proof document is required for library membership.',
            'id_proof.file' => 'The ID proof must be a valid file.',
            'id_proof.mimes' => 'The ID proof must be a JPEG, PNG, JPG, or PDF file.',
            'id_proof.max' => 'The ID proof file size must not exceed 4.5MB.',
            
            'timeslot_1_start.required' => 'Timeslot 1 start time is required.',
            'timeslot_1_start.date_format' => 'Please enter a valid time format for timeslot 1.',
            
            'timeslot_1_end.required' => 'Timeslot 1 end time is required.',
            'timeslot_1_end.date_format' => 'Please enter a valid time format for timeslot 1.',
            'timeslot_1_end.after' => 'Timeslot 1 end time must be after the start time.',
            
           /*  'timeslot_2_start.date_format' => 'Please enter a valid time format for timeslot 2.',
            
            'timeslot_2_end.date_format' => 'Please enter a valid time format for timeslot 2.',
            'timeslot_2_end.after' => 'Timeslot 2 end time must be after the start time.',
            
            'timeslot_3_start.date_format' => 'Please enter a valid time format for timeslot 3.',
            
            'timeslot_3_end.date_format' => 'Please enter a valid time format for timeslot 3.',
            'timeslot_3_end.after' => 'Timeslot 3 end time must be after the start time.', */
            
            'joining_date.required' => 'Joining date is required.',
            'joining_date.date' => 'Please enter a valid date.',
            'joining_date.after_or_equal' => 'The joining date must be today.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'mobile' => 'mobile number',
            'address' => 'address',
            'photo' => 'photo',
            'id_proof' => 'ID proof',
            'timeslot_1_start' => 'timeslot 1 start time',
            'timeslot_1_end' => 'timeslot 1 end time',
            // 'timeslot_2_start' => 'timeslot 2 start time',
            // 'timeslot_2_end' => 'timeslot 2 end time',
            // 'timeslot_3_start' => 'timeslot 3 start time',
            // 'timeslot_3_end' => 'timeslot 3 end time',
            'joining_date' => 'joining date',
        ];
    }
} 