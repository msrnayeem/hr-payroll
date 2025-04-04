<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SalaryCardController;
use App\Http\Controllers\SalaryComponentController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\InOutRecordController;
use App\Http\Controllers\LeaveCategoryController;
use App\Http\Controllers\LeaveApplicationController;
use App\Models\InOutRecord;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


// Protect all routes with auth middleware
Route::middleware(['auth'])->group(function () {

    // Employee Management
    Route::resource('employees', EmployeeController::class)->except(['destroy']);
    Route::post('/employees/{employee}/update-status', [EmployeeController::class, 'updateStatus'])->name('employees.update-status');

    //attendance resource routes
    Route::resource('attendances', AttendanceController::class)->only(['index']);
    Route::get('/attendances/requests', [AttendanceController::class, 'attendanceRequest'])->name('attendances-requests.index');
    Route::post('/attendances/requests', [AttendanceController::class, 'attendanceRequestStore'])->name('attendances-requests.store');
    Route::get('/attendances-requests/{id}/edit', [AttendanceController::class, 'attendanceRequestEdit'])->name('attendances-requests.edit');
    Route::put('/attendances-requests/{id}/update', [AttendanceController::class, 'attendanceRequestUpdate'])->name('attendances-requests.update');

    //in-out-records
    Route::get('/in-out-records', [InOutRecordController::class, 'index'])->name('in-out-records.index');

    // Salary Management
    Route::resource('salary-cards', SalaryCardController::class)->except(['destroy']);
    Route::get('salary-cards/{salaryCard}/history', [SalaryCardController::class, 'history'])->name('salary-cards.history');

    Route::get('components/{type?}', [SalaryComponentController::class, 'index'])
        ->name('salarycomponent.index');

    Route::get('components/{type?}/create', [SalaryComponentController::class, 'create'])
        ->name('salarycomponent.create');

    Route::resource('salary-component', SalaryComponentController::class)->except(['index', 'destroy', 'create']);


    // Payslips
    Route::resource('payslips', PayslipController::class);

    // Reports
    Route::resource('reports', ReportController::class);

    //holiday management
    Route::resource('holidays', HolidayController::class)->except(['create', 'destroy', 'show']);

    // Leave Management
    Route::resource('leave-categories', LeaveCategoryController::class)->except(['create', 'destroy', 'show']);

    Route::resource('leave-applications', LeaveApplicationController::class)->except(['create', 'destroy', 'show']);

    // Role and Permission Management
    Route::resource('roles', RoleController::class)->except(['show', 'destroy']);
    Route::resource('permissions', PermissionController::class)->except(['show', 'destroy']);

    // Settings (using group prefix for custom settings routes)
    Route::group(['prefix' => 'settings'], function () {
        Route::get('system', [SettingsController::class, 'system'])->name('settings.system');
        Route::get('users', [SettingsController::class, 'users'])->name('settings.users');
    });
});
