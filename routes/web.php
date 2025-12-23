<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;



Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('data', DataController::class);
Route::resource('notif', NotifikasiController::class);
