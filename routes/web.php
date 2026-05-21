<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventServiceRecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffActivitiesController;
use App\Http\Controllers\UserManagement;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', fn() => Auth::check() ? redirect('/dashboard') : redirect('/login'));

Route::get('/login', fn() => Auth::check() ? redirect('/dashboard') : view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Dashboards
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Shared modules
    Route::resource('beneficiaries', BeneficiaryController::class);
    Route::resource('events', EventController::class);
    Route::resource('attendance', AttendanceController::class);
    Route::resource('service-records', EventServiceRecordController::class);
    Route::post('attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.markAttendance');

    // Admin-only modules
    Route::resource('staff-activities', StaffActivitiesController::class);
    Route::resource('user-management', UserManagement::class);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',                           [ReportController::class, 'index'])->name('index');
        Route::get('/beneficiaries',              [ReportController::class, 'beneficiaries'])->name('beneficiaries');
        Route::get('/events',                     [ReportController::class, 'events'])->name('events');
        Route::get('/attendance',                 [ReportController::class, 'attendance'])->name('attendance');
        Route::get('/service-records',            [ReportController::class, 'serviceRecords'])->name('service-records');

        Route::get('/beneficiaries/export',       [ReportController::class, 'exportBeneficiaries'])->name('beneficiaries.export');
        Route::get('/events/export',              [ReportController::class, 'exportEvents'])->name('events.export');
        Route::get('/attendance/export',          [ReportController::class, 'exportAttendance'])->name('attendance.export');
        Route::get('/service-records/export',     [ReportController::class, 'exportServiceRecords'])->name('service-records.export');

        Route::get('/beneficiaries/export-pdf',   [ReportController::class, 'exportBeneficiariesPdf'])->name('beneficiaries.export.pdf');
        Route::get('/events/export-pdf',          [ReportController::class, 'exportEventsPdf'])->name('events.export.pdf');
        Route::get('/attendance/export-pdf',      [ReportController::class, 'exportAttendancePdf'])->name('attendance.export.pdf');
        Route::get('/service-records/export-pdf', [ReportController::class, 'exportServiceRecordsPdf'])->name('service-records.export.pdf');
    });
});