<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController; // 💡 1. コントローラーを読み込む
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;

Route::get('/customer/{id}/edit', [CustomerController::class, 'edit']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 💡 2. ログイン認証（auth）が必要なグループの中に顧客管理ルートを追加
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✨ カルテ履歴（URLが長くて複雑なもの）を先に書く
    Route::get('/customers/{customer}/history/{visitHistory}/edit', [CustomerController::class, 'editHistory'])->name('customers.editHistory');
    Route::put('/customers/{customer}/history/{visitHistory}', [CustomerController::class, 'updateHistory'])->name('customers.updateHistory');
    Route::delete('/customers/{customer}/history/{visitHistory}', [CustomerController::class, 'destroyHistory'])->name('customers.destroyHistory');
    Route::post('/customers/{customer}/history', [CustomerController::class, 'storeHistory'])->name('customers.storeHistory');

    // ✨ その後にリソースルートを書く
    Route::resource('customers', CustomerController::class);

    // 📅 予約カレンダー画面の表示と、カレンダーからデータを読み込むためのルート
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/events', [ReservationController::class, 'getEvents'])->name('reservations.events');
    // 📅 予約の新規登録
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

    // ⭕ 予約を更新(Update)するためのルートを追記
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');

    // ⭕ 予約を削除(Destroy)するためのルートを追記
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

});

require __DIR__.'/auth.php';
