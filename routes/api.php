<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;

        /*
        |--------------------------------------------------------------------------
        | API Routes
        |--------------------------------------------------------------------------
        |
        | Here is where you can register API routes for your application. These
        | routes are loaded by the RouteServiceProvider and all of them will
        | be assigned to the "api" middleware group. Make something great!
        |
        */

        Route::post("auth/login", LoginController::class)->name("auth.login");

        Route::middleware('auth:sanctum')->group(function () {
            Route::apiResource('users', UserController::class);
            Route::apiResource('wallets', WalletController::class);
            Route::apiResource('transaction-categories', TransactionCategoryController::class);
            Route::apiResource('transactions', TransactionController::class);
            Route::apiResource('people', PersonController::class);

            // Additional transaction routes
            Route::get('wallets/{walletId}/transactions', [TransactionController::class, 'getByWallet']);
            Route::get('transaction-categories/{categoryId}/transactions', [TransactionController::class, 'getByCategory']);
            Route::get('transactions/type/{type}', [TransactionController::class, 'getByType']);
        });
