<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
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



use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SalaryCardController;
use App\Http\Controllers\EarnHeadController;
use App\Http\Controllers\DeductionCategoryController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;


// Employee Management
Route::resource('employees', EmployeeController::class);

// Salary Management
Route::resource('salary-cards', SalaryCardController::class);
Route::resource('earn-heads', EarnHeadController::class);
Route::resource('deduction-categories', DeductionCategoryController::class);

// Payslips
Route::resource('payslips', PayslipController::class);

// Reports
Route::resource('reports', ReportController::class);

// Settings (using group prefix for custom settings routes)
Route::group(['prefix' => 'settings'], function () {
    Route::get('system', [SettingsController::class, 'system'])->name('settings.system');
    Route::get('users', [SettingsController::class, 'users'])->name('settings.users');
});
