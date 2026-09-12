<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\MedicineScanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/health-profile/create', [HealthProfileController::class, 'create'])->name('health-profile.create');
    Route::post('/health-profile', [HealthProfileController::class, 'store'])->name('health-profile.store');
    Route::get('/health-profile/edit', [HealthProfileController::class, 'edit'])->name('health-profile.edit');
    Route::put('/health-profile', [HealthProfileController::class, 'update'])->name('health-profile.update');

    Route::get('/consultation', [ConsultationController::class, 'index'])->name('consultation.index');
    Route::post('/consultation', [ConsultationController::class, 'create'])->name('consultation.create');
    Route::get('/consultation/{consultation}', [ConsultationController::class, 'show'])->name('consultation.show');
    Route::post('/consultation/{consultation}/message', [ConsultationController::class, 'sendMessage'])->name('consultation.message');

    Route::get('/reminder', [ReminderController::class, 'index'])->name('reminder.index');
    Route::get('/reminder/create', [ReminderController::class, 'create'])->name('reminder.create');
    Route::post('/reminder', [ReminderController::class, 'store'])->name('reminder.store');
    Route::delete('/reminder/{reminder}', [ReminderController::class, 'destroy'])->name('reminder.destroy');
    Route::post('/reminder/{reminder}/taken', [ReminderController::class, 'markTaken'])->name('reminder.taken');

    Route::get('/medicine-scan', [MedicineScanController::class, 'index'])->name('medicine-scan.index');
    Route::get('/medicine-scan/create', [MedicineScanController::class, 'create'])->name('medicine-scan.create');
    Route::post('/medicine-scan', [MedicineScanController::class, 'store'])->name('medicine-scan.store');
    Route::get('/medicine-scan/{scan}', [MedicineScanController::class, 'show'])->name('medicine-scan.show');
    Route::delete('/medicine-scan/{scan}', [MedicineScanController::class, 'destroy'])->name('medicine-scan.destroy');
});

require __DIR__.'/auth.php';