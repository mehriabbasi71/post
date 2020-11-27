<?php

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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('images',\App\Http\Controllers\Api\ImageController::class);
    Route::apiResource('posts',\App\Http\Controllers\Api\PostController::class);
    Route::get('posts/activate/{id}',[\App\Http\Controllers\Api\PostController::class,'activate']);
});


Route::prefix('authenticate')
    ->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\AuthenticateController::class,'index']);
        Route::middleware('throttle:1,10')
            ->post('/verify', [\App\Http\Controllers\Api\AuthenticateController::class,'verify']);
    });

