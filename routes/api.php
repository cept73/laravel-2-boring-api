<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/activities')->group(function () {
    Route::post('/{key}',   [ApiController::class, 'loadActivities']);
    Route::post('/',        [ApiController::class, 'loadActivities']);
    Route::get('/{key}',    [ApiController::class, 'getActivity']);
    Route::get('/',         [ApiController::class, 'getActivities']);
    Route::delete('/{key}', [ApiController::class, 'deleteActivity']);
});
