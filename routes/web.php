<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management Routes
    Route::resource('users', UserController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'show' => 'users.show',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);

    // Employee Management Routes
    Route::resource('employees', EmployeeController::class)->names([
        'index' => 'employees.index',
        'create' => 'employees.create',
        'store' => 'employees.store',
        'show' => 'employees.show',
        'edit' => 'employees.edit',
        'update' => 'employees.update',
        'destroy' => 'employees.destroy',
    ]);

    // Department Management Routes
    Route::resource('departments', DepartmentController::class)->names([
        'index' => 'departments.index',
        'create' => 'departments.create',
        'store' => 'departments.store',
        'show' => 'departments.show',
        'edit' => 'departments.edit',
        'update' => 'departments.update',
        'destroy' => 'departments.destroy',
    ]);

    // Evaluation Routes
    Route::resource('evaluations', EvaluationController::class)->names([
        'index' => 'evaluations.index',
        'create' => 'evaluations.create',
        'store' => 'evaluations.store',
        'show' => 'evaluations.show',
        'edit' => 'evaluations.edit',
        'update' => 'evaluations.update',
        'destroy' => 'evaluations.destroy',
    ]);

    // Export Routes
    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('/evaluation/{evaluation}/pdf', [ExportController::class, 'exportEvaluationPdf'])->name('evaluation.pdf');
        Route::get('/evaluations/excel', [ExportController::class, 'exportEvaluationsExcel'])->name('evaluations.excel');
        Route::get('/summary-report', [ExportController::class, 'exportSummaryReport'])->name('summary-report');
        Route::get('/department-rankings', [ExportController::class, 'exportDepartmentRankings'])->name('department-rankings');
    });
});
