<?php

use App\Http\Controllers\Api\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'DMS API',
        'version' => '1.0.0',
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('documents', DocumentController::class);
});
