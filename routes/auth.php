<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\AuthController,
    ActivityController,
    CategoryController,
    EarningController,
    RecurringController,
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

# Auth
Route::controller(AuthController::class)->group(function () {
    Route::get('me', 'me')->name('me');
    Route::post('refresh', 'refresh')->name('refresh');
    Route::put('me', 'update')->name('me.update');
});

# Resources (api)
Route::apiResource('spaces', SpaceController::class);
Route::apiResource('earnings', EarningController::class);
Route::apiResource('spendings', SpendingController::class);
Route::apiResource('recurrings', RecurringController::class);
Route::apiResource('tags', TagController::class)->except('show');
Route::apiResource('categories', CategoryController::class)->except('show');

# Invokables
Route::get('activities', ActivityController::class)->name('user.activities');

# Detachables
Route::delete('spaces/{id}/detach', [SpaceController::class, 'detachTags'])->name('space.detach.tags');
Route::delete('earnings/{id}/detach', [EarningController::class, 'detachTags'])->name('earnings.detach.tags');
Route::delete('spendings/{id}/detach', [SpendingController::class, 'detachTags'])->name('spendings.detach.tags');
Route::delete('recurrings/{id}/detach', [RecurringController::class, 'detachTags'])->name('recurrings.detach.tags');

Route::get('tests', function () {
    //
});
