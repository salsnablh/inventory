<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => env('SERVICE_NAME', 'inventory'),
        'time' => now()->toISOString(),
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('pencatatan')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::post('/items', [InventoryController::class, 'store'])->name('store');
        Route::put('/items/{item}', [InventoryController::class, 'update'])->name('update');
        Route::delete('/items/{item}', [InventoryController::class, 'destroy'])->name('destroy');
        Route::post('/items/{item}/movement', [InventoryController::class, 'recordMovement'])->name('movement');
    });

    Route::prefix('cetak-laporan')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export.csv', [ReportController::class, 'exportCsv'])->name('export');
    });

    Route::prefix('notifikasi-komunikasi')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/messages', [NotificationController::class, 'store'])->name('store');
        Route::patch('/messages/{message}/sent', [NotificationController::class, 'markSent'])->name('sent');
    });
});
