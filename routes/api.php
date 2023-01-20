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

Route::post('/activities/{key}',    [ApiController::class, 'loadActivities']);
Route::post('/activities',          [ApiController::class, 'loadActivities']);
Route::get('/activities/{key}',     [ApiController::class, 'getActivity']);
Route::get('/activities',           [ApiController::class, 'getActivities']);
Route::delete('/activities/{key}',  [ApiController::class, 'deleteActivity']);
