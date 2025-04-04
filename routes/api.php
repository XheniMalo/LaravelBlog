<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Api\ApiPostsController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [ApiLoginController::class, 'login']);
Route::post('/createPost', [ApiPostsController::class, 'storePost'])->middleware(['auth:sanctum', 'idempotency']);

//Route::post('/delete', [ApiLoginController::class, 'deleteLoginToken'])->middleware('auth:sanctum');


