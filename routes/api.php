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
    // Route user corrigée
    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => $request->user()
        ]);
    });
    
    // Route logout corrigée
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Routes pour les phases
    Route::get('/phases/actives', [PhaseController::class, 'phasesActives']);
    Route::get('/phases/{phase}', [PhaseController::class, 'show']);
    Route::get('/phases', [PhaseController::class, 'index']);
    Route::post('/phases', [PhaseController::class, 'store']);
    Route::put('/phases/{phase}', [PhaseController::class, 'update']);
    Route::delete('/phases/{phase}', [PhaseController::class, 'destroy']);
    
    // Routes pour les thèmes
    Route::get('/themes', [ThemeController::class, 'allThemes']);
    Route::get('/themes/actifs', [ThemeController::class, 'themesActifs']);
    Route::get('/phases/{phaseId}/themes/actifs', [ThemeController::class, 'themesActifsParPhase']);
    Route::get('/themes/{theme}', [ThemeController::class, 'show']);
    Route::get('/admin/themes', [ThemeController::class, 'index']);
    Route::post('/admin/themes', [ThemeController::class, 'store']);
    Route::put('/admin/themes/{theme}', [ThemeController::class, 'update']);
    Route::delete('/admin/themes/{theme}', [ThemeController::class, 'destroy']);
    
    // Routes pour les questions
    Route::get('/themes/{themeId}/questions/actives', [QuestionController::class, 'questionsActivesParTheme']);
    Route::get('/phases/{phaseId}/questions/actives', [QuestionController::class, 'questionsActivesParPhase']);
    Route::get('/questions/{question}/avec-propositions', [QuestionController::class, 'questionAvecPropositions']);
    Route::get('/questions/{question}', [QuestionController::class, 'show']);
    Route::get('/admin/questions', [QuestionController::class, 'index']);
    Route::post('/admin/questions', [QuestionController::class, 'store']);
    Route::put('/admin/questions/{question}', [QuestionController::class, 'update']);
    Route::delete('/admin/questions/{question}', [QuestionController::class, 'destroy']);
    
    // Routes pour les propositions
    Route::get('/questions/{questionId}/propositions/actives', [PropositionController::class, 'propositionsActivesParQuestion']);
    Route::get('/questions/{questionId}/propositions', [PropositionController::class, 'propositionsPourQuestion']);
    Route::get('/propositions/{proposition}', [PropositionController::class, 'show']);
    Route::get('/admin/propositions', [PropositionController::class, 'index']);
    Route::post('/admin/propositions', [PropositionController::class, 'store']);
    Route::put('/admin/propositions/{proposition}', [PropositionController::class, 'update']);
    Route::delete('/admin/propositions/{proposition}', [PropositionController::class, 'destroy']);
    
    // Routes pour les réponses utilisateur
    Route::post('/reponses', [ReponseUtilisateurController::class, 'store']);
    Route::get('/reponses', [ReponseUtilisateurController::class, 'index']);
    Route::get('/phases/{phaseId}/reponses', [ReponseUtilisateurController::class, 'reponsesParPhase']);
    Route::get('/statistiques', [ReponseUtilisateurController::class, 'statistiquesUtilisateur']);
    Route::get('/statistiques/{userId}', [ReponseUtilisateurController::class, 'statistiquesUtilisateur']);
    
    // Routes ADMIN pour les réponses
    Route::get('/admin/reponses', [ReponseUtilisateurController::class, 'allReponses']);
    Route::delete('/admin/reponses/{reponseUtilisateur}', [ReponseUtilisateurController::class, 'destroy']);
    
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