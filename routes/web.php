<?php

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



Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


// Protect all routes with auth middleware
Route::middleware(['auth'])->group(function () {

    // Employee Management
    Route::resource('employees', EmployeeController::class)->except(['destroy']);

    // Salary Management
    Route::resource('salary-cards', SalaryCardController::class)->except(['destroy']);
    Route::get('salary-cards/{salaryCard}/history', [SalaryCardController::class, 'history'])->name('salary-cards.history');

    Route::get('components/{type?}', [SalaryComponentController::class, 'index'])
        ->name('salarycomponent.index');

    Route::resource('salary-component', SalaryComponentController::class)->except(['index', 'destroy']);


    // Payslips
    Route::resource('payslips', PayslipController::class);

    // Reports
    Route::resource('reports', ReportController::class);

    // Role and Permission Management
    Route::resource('roles', RoleController::class)->except(['show', 'destroy']);
    Route::resource('permissions', PermissionController::class)->except(['show', 'destroy']);

    // Settings (using group prefix for custom settings routes)
    Route::group(['prefix' => 'settings'], function () {
        Route::get('system', [SettingsController::class, 'system'])->name('settings.system');
        Route::get('users', [SettingsController::class, 'users'])->name('settings.users');
    });
});
