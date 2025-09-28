<?php

namespace App\Http\Controllers;

use App\Models\ReponseUtilisateur;
use App\Models\Question;
use App\Models\Proposition;
use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReponseUtilisateurController extends Controller
{
    /**
     * Soumettre une réponse à une question
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'proposition_id' => 'required|exists:propositions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $questionId = $request->question_id;
        $propositionId = $request->proposition_id;

        // Vérifier si l'utilisateur a déjà répondu à cette question
        $reponseExistante = ReponseUtilisateur::where('user_id', $user->id)
                                            ->where('question_id', $questionId)
                                            ->first();

        if ($reponseExistante) {
            return response()->json([
                'message' => 'Vous avez déjà répondu à cette question.'
            ], 422);
        }

        // Vérifier que la proposition appartient à la question
        $proposition = Proposition::where('id', $propositionId)
                                ->where('question_id', $questionId)
                                ->first();

        if (!$proposition) {
            return response()->json([
                'message' => 'Cette proposition ne correspond pas à la question.'
            ], 422);
        }

        // Vérifier que la question est active
        $question = Question::where('id', $questionId)
                          ->where('est_actif', true)
                          ->with('theme.phase')
                          ->first();

        if (!$question) {
            return response()->json([
                'message' => 'Cette question n\'est pas disponible.'
            ], 422);
        }

        // Calculer les points obtenus
        $points = 0;
        if ($proposition->est_correcte) {
            $points = $question->theme->phase->points_par_question;
        }

        // Créer la réponse utilisateur
        $reponseUtilisateur = ReponseUtilisateur::create([
            'user_id' => $user->id,
            'question_id' => $questionId,
            'proposition_id' => $propositionId,
            'points_obtenus' => $points,
            'date_reponse' => now()
        ]);

        $reponseUtilisateur->load(['question', 'proposition']);

        return response()->json([
            'message' => 'Réponse enregistrée avec succès',
            'reponse' => $reponseUtilisateur,
            'est_correcte' => $proposition->est_correcte,
            'points_obtenus' => $points
        ], 201);
    }

    /**
     * Récupérer les réponses d'un utilisateur
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $reponses = ReponseUtilisateur::where('user_id', $user->id)
                                    ->with(['question', 'question.theme.phase', 'proposition'])
                                    ->orderBy('date_reponse', 'desc')
                                    ->get();

        return response()->json([
            'reponses' => $reponses
        ]);
    }

    /**
     * Récupérer les réponses d'un utilisateur pour une phase spécifique
     */
    public function reponsesParPhase(Request $request, $phaseId)
    {
        $user = $request->user();

        $reponses = ReponseUtilisateur::where('user_id', $user->id)
                                    ->whereHas('question.theme', function($query) use ($phaseId) {
                                        $query->where('phase_id', $phaseId);
                                    })
                                    ->with(['question', 'question.theme.phase', 'proposition'])
                                    ->orderBy('date_reponse', 'desc')
                                    ->get();

        return response()->json([
            'reponses' => $reponses
        ]);
    }

    /**
     * Récupérer les statistiques de réponses d'un utilisateur (pour admin)
     */
    public function statistiquesUtilisateur(Request $request, $userId = null)
    {
        // Vérification admin si userId est fourni
        if ($userId && !$request->user()->is_admin) {
            return response()->json([
                'message' => 'Accès non autorisé. Admin requis.'
            ], 403);
        }

        $targetUserId = $userId ?: $request->user()->id;

        $statistiques = ReponseUtilisateur::where('user_id', $targetUserId)
                                        ->select([
                                            DB::raw('COUNT(*) as total_reponses'),
                                            DB::raw('SUM(points_obtenus) as total_points'),
                                            DB::raw('SUM(CASE WHEN propositions.est_correcte = 1 THEN 1 ELSE 0 END) as bonnes_reponses')
                                        ])
                                        ->join('propositions', 'reponse_utilisateurs.proposition_id', '=', 'propositions.id')
                                        ->first();

        return response()->json([
            'statistiques' => $statistiques
        ]);
    }

    /**
     * Récupérer toutes les réponses (pour admin)
     */
    public function allReponses(Request $request)
    {
        // Vérification admin
        if (!$request->user()->is_admin) {
            return response()->json([
                'message' => 'Accès non autorisé. Admin requis.'
            ], 403);
        }

        $reponses = ReponseUtilisateur::with(['user', 'question', 'question.theme.phase', 'proposition'])
                                    ->orderBy('date_reponse', 'desc')
                                    ->get();

        return response()->json([
            'reponses' => $reponses
        ]);
    }

    /**
     * Supprimer une réponse (pour admin)
     */
    public function destroy(Request $request, ReponseUtilisateur $reponseUtilisateur)
    {
        // Vérification admin
        if (!$request->user()->is_admin) {
            return response()->json([
                'message' => 'Accès non autorisé. Admin requis.'
            ], 403);
        }

        $reponseUtilisateur->delete();

        return response()->json([
            'message' => 'Réponse supprimée avec succès'
        ]);
    }
}