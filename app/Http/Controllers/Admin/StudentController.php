<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;

class StudentController extends Controller
{
    public function index()
    {
        $students = StudentProfile::with(['user', 'seat'])->get();
        return view('admin.students.index', compact('students'));
    }
    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
} 