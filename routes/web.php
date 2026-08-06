<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SuratDinasController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Travels Custom Routes (harus didefinisikan sebelum Route::resource)
    Route::get('/travels/export', [TravelController::class, 'exportCsv'])->name('travels.export');
    Route::get('/travels/data/json', [TravelController::class, 'getDataJson'])->name('travels.json');
    Route::post('/travels/bulk-delete', [TravelController::class, 'bulkDelete'])->name('travels.bulk-delete');
    Route::get('/travels/employees/by-aplikasi', [TravelController::class, 'getEmployeesByAplikasi'])->name('travels.employees.by-aplikasi');
    Route::patch('/travels/{travel}/accumulation', [TravelController::class, 'updateAccumulation'])->name('travels.accumulation');
    Route::patch('/travels/{travel}/status', [TravelController::class, 'toggleStatus'])->name('travels.status');
    Route::post('/travels/{travel}/send-wa-reminder', [TravelController::class, 'sendWaReminder'])->name('travels.wa-reminder');
    Route::get('/travels/{travel}/print', [TravelController::class, 'printSpd'])->name('travels.print');
    Route::resource('travels', TravelController::class);
    
    // Rekap Surat Dinas
    Route::get('/surat-dinas/export', [SuratDinasController::class, 'export'])->name('surat-dinas.export');
    Route::resource('surat-dinas', SuratDinasController::class);
    
    // WA Reminder Center
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::post('/reminders/send/{travel}', [ReminderController::class, 'sendSingle'])->name('reminders.send');
    Route::post('/reminders/send-all', [ReminderController::class, 'sendAll'])->name('reminders.send-all');
    Route::get('/reminders/history', [ReminderController::class, 'history'])->name('reminders.history');
    
    // Employees
    Route::get('/employees/data/json', [EmployeeController::class, 'getDataJson'])->name('employees.json');
    Route::resource('employees', EmployeeController::class);
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
});

require __DIR__.'/auth.php';    