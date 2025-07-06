<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('seats', App\Http\Controllers\Admin\SeatController::class);
    Route::resource('students', App\Http\Controllers\Admin\StudentController::class);
    Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    
    // Overstay API routes
    Route::get('/overstays', [App\Http\Controllers\Admin\OverstayController::class, 'getOverstayingStudents'])->name('overstays.api');
    Route::post('/overstays/checkout', [App\Http\Controllers\Admin\OverstayController::class, 'forceCheckOut'])->name('overstays.checkout');
    
    // Test route for overstay functionality (remove in production)
    Route::get('/test-overstay', function() {
        $overstayService = new \App\Services\OverstayDetectionService();
        $overstays = $overstayService->getOverstayingStudents();
        return response()->json([
            'overstays' => $overstays,
            'count' => count($overstays),
            'current_time' => now()->format('H:i:s'),
            'today' => now()->toDateString()
        ]);
    })->name('test.overstay');
});

// Student routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/join', [App\Http\Controllers\Student\LibraryJoinController::class, 'showForm'])->name('join.form');
    Route::post('/join', [App\Http\Controllers\Student\LibraryJoinController::class, 'submitForm'])->name('join.submit');
    Route::get('/profile', [App\Http\Controllers\Student\DashboardController::class, 'profile'])->name('profile');
    Route::post('/check-in', [App\Http\Controllers\Student\TimesheetController::class, 'checkIn'])->name('checkin');
    Route::post('/check-out', [App\Http\Controllers\Student\TimesheetController::class, 'checkOut'])->name('checkout');
    
 
    
});

require __DIR__.'/auth.php';
