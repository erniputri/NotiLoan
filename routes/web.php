<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

//Fitur Route

Route::group(['middleware' => ['checkislogin']], function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('data', DataController::class);
    Route::resource('notif', NotifikasiController::class);
});

/* AUTH */
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerView'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/peminjaman/export', [ExportController::class, 'export']);

