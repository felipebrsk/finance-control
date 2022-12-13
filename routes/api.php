<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\AuthController,
    Auth\ForgotPasswordController,
    Auth\ResetPasswordController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
});

Route::post('password/send', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.forgot');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::get('tests', function () {
    return view('emails.password-changed');
});
