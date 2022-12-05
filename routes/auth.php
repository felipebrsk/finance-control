<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\AuthController,
    ActivityController,
};

/*
|--------------------------------------------------------------------------
| AUTH Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" and "auth:api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('me', 'me')->name('me');
    Route::post('refresh', 'refresh')->name('refresh');
});

Route::prefix('me')->group(function () {
    Route::get('activities', ActivityController::class)->name('me.activities');
});

Route::get('tests', function () {
    //
});
