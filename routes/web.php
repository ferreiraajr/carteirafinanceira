<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class,'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::controller(DepositController::class)->group(function () {
        Route::get('/deposit', 'create')->name('deposit.create');
        Route::post('/deposit', 'store')->name('deposit.store');
    });

    Route::controller(TransferController::class)->group(function () {
        Route::get('/transfer', 'create')->name('transfer.create');
        Route::post('/transfer', 'store')->name('transfer.store');
    });

    Route::get('/wallets/by-email/{email}', function ($email) {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        $wallet = $user->wallet;
        if (!$wallet) {
            return response()->json(['message' => 'Carteira não encontrada para este usuário'], 404);
        }
        return response()->json([
            'wallet_id' => $wallet->id,
            'user_name' => $user->name,
        ]);
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
