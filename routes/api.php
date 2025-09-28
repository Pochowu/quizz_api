<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\ThemeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Routes pour les phases
    Route::get('/phases/actives', [PhaseController::class, 'phasesActives']);
    Route::get('/phases/{phase}', [PhaseController::class, 'show']);
    Route::get('/phases', [PhaseController::class, 'index']);
    Route::post('/phases', [PhaseController::class, 'store']);
    Route::put('/phases/{phase}', [PhaseController::class, 'update']);
    Route::delete('/phases/{phase}', [PhaseController::class, 'destroy']);
    
    // Routes UTILISATEUR pour les thèmes (accessibles à tous)
    Route::get('/themes', [ThemeController::class, 'allThemes']);
    Route::get('/themes/actifs', [ThemeController::class, 'themesActifs']);
    Route::get('/phases/{phaseId}/themes/actifs', [ThemeController::class, 'themesActifsParPhase']);
    Route::get('/themes/{theme}', [ThemeController::class, 'show']);
    
    // Routes ADMIN pour les thèmes (CRUD complet)
    Route::get('/admin/themes', [ThemeController::class, 'index']);
    Route::post('/admin/themes', [ThemeController::class, 'store']);
    Route::put('/admin/themes/{theme}', [ThemeController::class, 'update']);
    Route::delete('/admin/themes/{theme}', [ThemeController::class, 'destroy']);
});

// Route pour tester l'authentification
Route::middleware('auth:sanctum')->get('/test-auth', function (Request $request) {
    return response()->json([
        'message' => 'Authentification réussie',
        'user' => $request->user()
    ]);
});