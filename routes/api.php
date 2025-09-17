<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EscortApi;

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

// Public API routes - accessible for escort form submissions
Route::apiResource('escort', EscortApi::class)->only(['store']);

// Session stats endpoint (public for monitoring)
Route::get('/session-stats', [EscortApi::class, 'getSessionStats']);

// Protected API routes - require web session authentication (IGD Staff only)
Route::middleware('auth:web')->group(function () {
    Route::apiResource('escort', EscortApi::class)->except(['store']);
});
