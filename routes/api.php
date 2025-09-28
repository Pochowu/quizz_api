<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PropositionController;
use App\Http\Controllers\ReponseUtilisateurController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    // ... routes existantes ...
    
    // Routes pour les réponses utilisateur
    Route::post('/reponses', [ReponseUtilisateurController::class, 'store']);
    Route::get('/reponses', [ReponseUtilisateurController::class, 'index']);
    Route::get('/phases/{phaseId}/reponses', [ReponseUtilisateurController::class, 'reponsesParPhase']);
    Route::get('/statistiques', [ReponseUtilisateurController::class, 'statistiquesUtilisateur']);
    Route::get('/statistiques/{userId}', [ReponseUtilisateurController::class, 'statistiquesUtilisateur']);
    
    // Routes ADMIN pour les réponses
    Route::get('/admin/reponses', [ReponseUtilisateurController::class, 'allReponses']);
    Route::delete('/admin/reponses/{reponseUtilisateur}', [ReponseUtilisateurController::class, 'destroy']);
});

// Route pour tester l'authentification
Route::middleware('auth:sanctum')->get('/test-auth', function (Request $request) {
    return response()->json([
        'message' => 'Authentification réussie',
        'user' => $request->user()
    ]);
});