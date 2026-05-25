<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController; // 💡 1. コントローラーを読み込む
use Illuminate\Support\Facades\Route;

Route::get('/customer/{id}/edit', [CustomerController::class, 'edit']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
});

require __DIR__.'/auth.php';
