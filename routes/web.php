<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'hr') {
            return redirect()->route('hr.dashboard');
        }
        return redirect()->route('employee.dashboard');
    }
    return redirect('/login');
});

// Employee Routes
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('dashboard');
    
    // Attendances
    Route::get('/attendances', [\App\Http\Controllers\Employee\AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/attendances/check-in', [\App\Http\Controllers\Employee\AttendanceController::class, 'checkIn'])->name('attendances.check-in');
    Route::post('/attendances/check-out', [\App\Http\Controllers\Employee\AttendanceController::class, 'checkOut'])->name('attendances.check-out');
    
    // Leave Requests
    Route::resource('leave-requests', \App\Http\Controllers\Employee\LeaveRequestController::class)->except(['destroy', 'edit', 'update']);
    
    // Salaries
    Route::get('/salaries', [\App\Http\Controllers\Employee\SalaryController::class, 'index'])->name('salaries.index');
});

// HR Admin Routes
Route::middleware(['auth', 'role:hr'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\HR\DashboardController::class, 'index'])->name('dashboard');
    
    // Master Data
    Route::resource('departments', \App\Http\Controllers\HR\DepartmentController::class);
    Route::resource('positions', \App\Http\Controllers\HR\PositionController::class);
    Route::resource('schedules', \App\Http\Controllers\HR\WorkScheduleController::class);
    Route::resource('employees', \App\Http\Controllers\HR\EmployeeController::class);
    
    // Transactional Data
    Route::get('/attendances', [\App\Http\Controllers\HR\AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/leave-requests', [\App\Http\Controllers\HR\LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::post('/leave-requests/{leaveRequest}/approve', [\App\Http\Controllers\HR\LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [\App\Http\Controllers\HR\LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    
    // Salaries
    Route::resource('salaries', \App\Http\Controllers\HR\SalaryController::class);
    Route::post('/salaries/{salary}/release', [\App\Http\Controllers\HR\SalaryController::class, 'release'])->name('salaries.release');
});

// Default Breeze profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
