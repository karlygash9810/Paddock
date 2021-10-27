<?php

use App\Http\Controllers\PaddockController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HistoryDayController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/paddock', [PaddockController::class, 'index']);
Route::put('/paddock', [PaddockController::class, 'update']);
Route::get('/history', [HistoryController::class, 'index']);
Route::get('/history/day', [HistoryDayController::class, 'index']);
