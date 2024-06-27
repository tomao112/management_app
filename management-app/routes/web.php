<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance', [AttendanceController::class, 'showAttendance'])->name('attendance');
    Route::post('/start-break', [AttendanceController::class, 'startBreak'])->name('start.break');
    Route::post('/end-break', [AttendanceController::class, 'endBreak'])->name('end.break');
    Route::get('/break-status', [AttendanceController::class, 'getBreakStatus'])->name('break.status');
    Route::get('/attendance/status', [AttendanceController::class, 'getAttendanceStatus'])->name('attendance.status');
    Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('clock.in');
    Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('clock.out');
    Route::get('/attendance/edit/{id}', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::post('/attendance/update/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
});
