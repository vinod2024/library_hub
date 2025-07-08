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
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:max_width=1000,max_height=1000',
            'id_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'timeslot_start' => 'required|date_format:H:i',
            'timeslot_end' => 'required|date_format:H:i|after:timeslot_start',
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
            'photo.max' => 'The photo size must not exceed 2MB.',
            'photo.dimensions' => 'The photo dimensions must be less than  1000x1000 pixels.',
            
            'id_proof.required' => 'An ID proof document is required for library membership.',
            'id_proof.file' => 'The ID proof must be a valid file.',
            'id_proof.mimes' => 'The ID proof must be a JPEG, PNG, JPG, or PDF file.',
            'id_proof.max' => 'The ID proof file size must not exceed 2MB.',
            
            'timeslot_start.required' => 'Start time is required.',
            'timeslot_start.date_format' => 'Please enter a valid time format.',
            
            'timeslot_end.required' => 'End time is required.',
            'timeslot_end.date_format' => 'Please enter a valid time format.',
            'timeslot_end.after' => 'The end time must be after the start time.',
            
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
            'timeslot_start' => 'start time',
            'timeslot_end' => 'end time',
            'joining_date' => 'joining date',
        ];
    }
} 