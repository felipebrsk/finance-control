<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\AuthController,
    ActivityController,
    CategoryController,
    EarningController,
    SpaceController,
    SpendingController,
    TagController,
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

Route::apiResource('spaces', SpaceController::class);
Route::apiResource('earnings', EarningController::class);
Route::apiResource('spendings', SpendingController::class);
Route::apiResource('tags', TagController::class)->except('show');
Route::apiResource('categories', CategoryController::class)->except('show');

Route::get('activities', ActivityController::class)->name('user.activities');
Route::delete('spaces/{id}/detach', [SpaceController::class, 'detachTags'])->name('space.detach.tags');
Route::delete('earnings/{id}/detach', [EarningController::class, 'detachTags'])->name('earnings.detach.tags');
Route::delete('spendings/{id}/detach', [SpendingController::class, 'detachTags'])->name('spendings.detach.tags');

Route::get('tests', function () {
    //
});
