<?php

use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth:web', 'verified'])->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::post('taskAssign', [TaskController::class, 'taskAssign'])->name('task-assign');
});


// Client Routes
Route::prefix('client')->name('client.')->group(function () {
    Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [ClientAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [ClientAuthController::class, 'register'])->name('register');
    Route::post('/login', [ClientAuthController::class, 'login']);
    Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:client')->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        Route::post('/tasks/{task}/complete', [ClientDashboardController::class, 'markComplete'])->name('tasks.complete');
    });
});


require __DIR__ . '/auth.php';
