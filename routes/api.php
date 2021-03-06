<?php

use App\Http\Controllers\Api\AnimeDetailController;
use App\Http\Controllers\Api\AnimeMovieController;
use App\Http\Controllers\Api\AnimeOvaController;
use App\Http\Controllers\Api\AnimeSearchController;
use App\Http\Controllers\Api\AnimeTvController;
use App\Http\Controllers\Api\AuthController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tv/{season?}/{year?}', AnimeTvController::class);
    Route::get('/movies/{season?}/{year?}', AnimeMovieController::class);
    Route::get('/ovas/{season?}/{year?}', AnimeOvaController::class);
    Route::get('/anime/{anime}', AnimeDetailController::class);
    Route::get('/search', AnimeSearchController::class);
});
