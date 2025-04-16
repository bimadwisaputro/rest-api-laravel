<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::apiResource('employee', EmployeeController::class);
    // Route::get('/user', [AuthController::class, 'getUser']);
    // Route::put('/user', [AuthController::class, 'updateUser']);
});
