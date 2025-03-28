<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SalaryCardController;
use App\Http\Controllers\EarnHeadController;
use App\Http\Controllers\DeductionCategoryController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RolePermissionController;

// Role Management
Route::get('roles', [RolePermissionController::class, 'index'])->name('roles.index');
Route::get('roles/create', [RolePermissionController::class, 'create'])->name('roles.create');
Route::post('roles', [RolePermissionController::class, 'store'])->name('roles.store');
Route::get('roles/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit');
Route::put('roles/{role}', [RolePermissionController::class, 'update'])->name('roles.update');


// Permission Management
Route::get('permissions', [RolePermissionController::class, 'permissionIndex'])->name('permissions.index');
Route::get('permissions/create', [RolePermissionController::class, 'createPermission'])->name('permissions.create');
Route::post('permissions', [RolePermissionController::class, 'storePermission'])->name('permissions.store');
Route::get('permissions/{permission}/edit', [RolePermissionController::class, 'editPermission'])->name('permissions.edit');
Route::put('permissions/{permission}', [RolePermissionController::class, 'updatePermission'])->name('permissions.update');


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
