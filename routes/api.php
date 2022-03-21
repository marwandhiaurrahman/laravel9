<?php

use App\Http\Controllers\API\AntrianBPJSController;
use App\Http\Controllers\API\RegisterController;
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

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [RegisterController::class, 'profile']);
    Route::post('logout', [RegisterController::class, 'logout']);
});

Route::get('token', [AntrianBPJSController::class, 'token']);
Route::prefix('antrian')->middleware('auth:sanctum')->group(function () {
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [RegisterController::class, 'profile']);
    Route::post('logout', [RegisterController::class, 'logout']);
});

Route::prefix('bpjs')->group(function () {
    Route::get('profile', [RegisterController::class, 'profile']);
});
