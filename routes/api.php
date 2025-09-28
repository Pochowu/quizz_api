<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PropositionController;
use App\Http\Controllers\ReponseUtilisateurController;
use App\Http\Controllers\StatistiquesUtilisateurController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    // ... routes existantes ...
    
    // Routes pour les statistiques
    Route::get('/statistiques-utilisateur', [StatistiquesUtilisateurController::class, 'index']);
    Route::get('/classement', [StatistiquesUtilisateurController::class, 'classement']);
    Route::get('/phases/{phaseId}/classement', [StatistiquesUtilisateurController::class, 'classementParPhase']);
    
    // Routes ADMIN pour les statistiques
    Route::get('/admin/statistiques-utilisateur/{userId}', [StatistiquesUtilisateurController::class, 'show']);
    Route::post('/admin/statistiques/mettre-a-jour', [StatistiquesUtilisateurController::class, 'mettreAJourToutesStatistiques']);
});

// Route pour tester l'authentification
Route::middleware('auth:sanctum')->get('/test-auth', function (Request $request) {
    return response()->json([
        'message' => 'Authentification réussie',
        'user' => $request->user()
    ]);
});