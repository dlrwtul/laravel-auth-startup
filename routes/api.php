<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/change-password', [PasswordController::class, 'changePassword'])->middleware(['auth', 'verified:api'])->name('password.change');
    Route::post('/forgot-password', [PasswordController::class, 'forgotPassword'])->name('password.request');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.reset');
    Route::post('/email/change', [EmailVerificationController::class, 'changeEmail'])->middleware(['auth'])->name('email.change');
    Route::get('/email/verification', [EmailVerificationController::class, 'verify'])->middleware('api')->name('verification.verify');
    Route::post('/email/resend-verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
});

Route::middleware('auth')->group(function () {
    Route::put('users/{user}/enable-or-disable', [UserController::class, 'enableOrDisable'])->name('users.enableOrDisable');

    Route::apiResources(
        [
            'users' => UserController::class,
        ]
    );
});
