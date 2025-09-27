<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
  
    Route::get('/phases/actives', [PhaseController::class, 'phasesActives']);
    Route::get('/phases/{phase}', [PhaseController::class, 'show']);
    
   
    Route::get('/phases', [PhaseController::class, 'index']);
    Route::post('/phases', [PhaseController::class, 'store']);
    Route::put('/phases/{phase}', [PhaseController::class, 'update']);
    Route::delete('/phases/{phase}', [PhaseController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->get('/test-auth', function (Request $request) {
    return response()->json([
        'message' => 'Authentification réussie',
        'user' => $request->user()
    ]);
});