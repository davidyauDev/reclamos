<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('health', function (Request $request) {
    return response()->json([
        'status' => 'ok',
        'app'    => config('app.name'),
        'env'    => app()->environment(),
        'time'   => now()->toIso8601String(),
    ], 200);
});
