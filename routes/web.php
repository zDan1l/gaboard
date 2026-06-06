<?php

use App\Http\Controllers\AttendanceEntryController;
use App\Http\Controllers\AttendanceScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerSatisfactionScoreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\KpiReportController;
use App\Http\Controllers\KpiTargetController;
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
    Route::prefix('evaluations')->name('evaluations.')->group(function () {
        Route::get('/', [EvaluationController::class, 'index'])->name('index');
        Route::get('/create', [EvaluationController::class, 'create'])->name('create');
        Route::post('/batch-generate', [EvaluationController::class, 'batchGenerate'])->name('batch-generate');
        Route::post('/', [EvaluationController::class, 'store'])->name('store');
        Route::get('/{evaluation}/edit', [EvaluationController::class, 'edit'])->name('edit');
        Route::get('/{evaluation}', [EvaluationController::class, 'show'])->name('show');
        Route::put('/{evaluation}', [EvaluationController::class, 'update'])->name('update');
        Route::delete('/{evaluation}', [EvaluationController::class, 'destroy'])->name('destroy');
    });

    // Export Routes
    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('/evaluation/{evaluation}/pdf', [ExportController::class, 'exportEvaluationPdf'])->name('evaluation.pdf');
        Route::get('/evaluations/excel', [ExportController::class, 'exportEvaluationsExcel'])->name('evaluations.excel');
        Route::get('/summary-report', [ExportController::class, 'exportSummaryReport'])->name('summary-report');
        Route::get('/department-rankings', [ExportController::class, 'exportDepartmentRankings'])->name('department-rankings');
    });

    // KPI Management Routes (Manager/HR)
    Route::prefix('kpi-targets')->name('kpi-targets.')->group(function () {
        Route::get('/', [KpiTargetController::class, 'indexView'])->name('index');
        Route::get('/create', [KpiTargetController::class, 'createView'])->name('create');
        Route::post('/', [KpiTargetController::class, 'store'])->name('store');
        Route::get('/{kpiTarget}/edit', [KpiTargetController::class, 'editView'])->name('edit');
        Route::get('/{kpiTarget}', [KpiTargetController::class, 'show'])->name('show');
        Route::put('/{kpiTarget}', [KpiTargetController::class, 'update'])->name('update');
        Route::delete('/{kpiTarget}', [KpiTargetController::class, 'destroy'])->name('destroy');
    });

    // Employee KPI Report Routes
    Route::prefix('kpi-reports')->name('kpi-reports.')->group(function () {
        Route::get('/', [KpiReportController::class, 'indexView'])->name('index');
        Route::get('/create/{kpiTarget}', [KpiReportController::class, 'createView'])->name('create');
        Route::post('/', [KpiReportController::class, 'store'])->name('store');
        Route::get('/{kpiReport}/edit', [KpiReportController::class, 'editView'])->name('edit');
        Route::put('/{kpiReport}', [KpiReportController::class, 'update'])->name('update');
        Route::delete('/{kpiReport}', [KpiReportController::class, 'destroy'])->name('destroy');
        Route::get('/{kpiReport}', [KpiReportController::class, 'show'])->name('show');
    });

    // My Targets Route (for employees to see their targets)
    Route::get('/my-kpi-targets', [KpiTargetController::class, 'myTargets'])->name('my-kpi-targets');

    // Attendance Schedule Routes (HR/Manager)
    Route::prefix('attendance-schedules')->name('attendance-schedules.')->group(function () {
        Route::get('/', [AttendanceScheduleController::class, 'indexView'])->name('index');
        Route::get('/create', [AttendanceScheduleController::class, 'createView'])->name('create');
        Route::get('/today', [AttendanceScheduleController::class, 'today'])->name('today');
        Route::post('/', [AttendanceScheduleController::class, 'store'])->name('store');
        Route::get('/{attendanceSchedule}/edit', [AttendanceScheduleController::class, 'editView'])->name('edit');
        Route::get('/{attendanceSchedule}', [AttendanceScheduleController::class, 'show'])->name('show');
        Route::put('/{attendanceSchedule}', [AttendanceScheduleController::class, 'update'])->name('update');
        Route::delete('/{attendanceSchedule}', [AttendanceScheduleController::class, 'destroy'])->name('destroy');
    });

    // Attendance Entry Routes
    Route::prefix('attendance-entries')->name('attendance-entries.')->group(function () {
        Route::get('/', [AttendanceEntryController::class, 'index'])->name('index');
        Route::get('/manage', [AttendanceEntryController::class, 'manageView'])->name('manage');
        Route::get('/my-attendance', [AttendanceEntryController::class, 'indexView'])->name('my-attendance');
        Route::post('/', [AttendanceEntryController::class, 'store'])->name('store');
        Route::post('/clock-in', [AttendanceEntryController::class, 'clockIn'])->name('clock-in');
        Route::post('/clock-out', [AttendanceEntryController::class, 'clockOut'])->name('clock-out');
        Route::get('/{attendanceEntry}', [AttendanceEntryController::class, 'show'])->name('show');
        Route::put('/{attendanceEntry}', [AttendanceEntryController::class, 'update'])->name('update');
        Route::delete('/{attendanceEntry}', [AttendanceEntryController::class, 'destroy'])->name('destroy');
    });

    // Customer Satisfaction Score Routes (Manager/HR)
    Route::prefix('customer-satisfaction')->name('customer-satisfaction.')->group(function () {
        Route::get('/', [CustomerSatisfactionScoreController::class, 'indexView'])->name('index');
        Route::get('/create', [CustomerSatisfactionScoreController::class, 'createView'])->name('create');
        Route::get('/my-scores-data', [CustomerSatisfactionScoreController::class, 'myScores'])->name('my-scores-data');
        Route::get('/my-scores', [CustomerSatisfactionScoreController::class, 'myScoresView'])->name('my-scores');
        Route::get('/employee/{employeeId}', [CustomerSatisfactionScoreController::class, 'employeeScores'])->name('employee-scores');
        Route::post('/', [CustomerSatisfactionScoreController::class, 'store'])->name('store');
        Route::get('/{customerSatisfactionScore}/edit', [CustomerSatisfactionScoreController::class, 'editView'])->name('edit');
        Route::get('/{customerSatisfactionScore}', [CustomerSatisfactionScoreController::class, 'show'])->name('show');
        Route::put('/{customerSatisfactionScore}', [CustomerSatisfactionScoreController::class, 'update'])->name('update');
        Route::delete('/{customerSatisfactionScore}', [CustomerSatisfactionScoreController::class, 'destroy'])->name('destroy');
    });
});
