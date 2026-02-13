<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ExportController;

//Fitur Route

Route::group(['middleware' => ['checkislogin']], function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    // Route::resource('data', DataController::class);
    Route::resource('notif', NotifikasiController::class);

    Route::prefix('data')->name('data.')->group(function () {

        // INDEX (INI YANG KURANG)
        Route::get('/', [DataController::class, 'index'])->name('index');

        // EDIT & UPDATE
        // =========================
        // EDIT WIZARD
        // =========================
        Route::get('/{id}/edit/step-1', [DataController::class, 'editStep1'])->name('edit.step1');
        Route::put('/{id}/edit/step-1', [DataController::class, 'updateStep1'])->name('update.step1');

        Route::get('/{id}/edit/step-2', [DataController::class, 'editStep2'])->name('edit.step2');
        Route::put('/{id}/edit/step-2', [DataController::class, 'updateStep2'])->name('update.step2');

        Route::get('/{id}/edit/step-3', [DataController::class, 'editStep3'])->name('edit.step3');
        Route::put('/{id}/edit/step-3', [DataController::class, 'updateStep3'])->name('update.step3');

        // DELETE
        Route::delete('/delete/{id}', [DataController::class, 'destroy'])->name('destroy');

        //SHOW
        Route::get('/{id}', [DataController::class, 'show'])->name('show');

        // =========================
        // WIZARD TAMBAH DATA
        // =========================
        Route::get('/create/step-1', [DataController::class, 'createStep1'])->name('create.step1');
        Route::post('/create/step-1', [DataController::class, 'storeStep1'])->name('store.step1');

        Route::get('/create/step-2', [DataController::class, 'createStep2'])->name('create.step2');
        Route::post('/create/step-2', [DataController::class, 'storeStep2'])->name('store.step2');

        Route::get('/create/step-3', [DataController::class, 'createStep3'])->name('create.step3');
        Route::post('/create/step-3', [DataController::class, 'storeFinal'])->name('store.final');

        //Route Exsport
        Route::get('/export/excel', [DataController::class, 'exportExcel'])
            ->name('export.excel');
    });

    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('index');
        Route::get('/create', [PembayaranController::class, 'create'])->name('create');
        Route::post('/store', [PembayaranController::class, 'store'])->name('store');
    });
});

Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerView'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/peminjaman/export', [ExportController::class, 'export']);

