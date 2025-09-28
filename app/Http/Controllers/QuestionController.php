<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Vérifier si l'utilisateur est admin
     */
    private function checkAdmin($user)
    {
        if (!$user || !$user->is_admin) {
            return response()->json([
                'message' => 'Accès non autorisé. Admin requis.'
            ], 403);
        }
        return null;
    }

    /**
     * Display a listing of the resource (pour admin - toutes les données)
     */
    public function index(Request $request)
    {
        // Vérification admin
        $adminCheck = $this->checkAdmin($request->user());
        if ($adminCheck) return $adminCheck;

        $questions = Question::with(['theme', 'theme.phase'])
                           ->orderBy('theme_id')
                           ->orderBy('ordre')
                           ->get();
        
        return response()->json([
            'questions' => $questions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Vérification admin
    $adminCheck = $this->checkAdmin($request->user());
    if ($adminCheck) return $adminCheck;

    try {
        // Création directe sans validation pour tester
        $question = Question::create([
            'texte' => $request->texte,
            'explication' => $request->explication,
            'theme_id' => (int)$request->theme_id,
            'temps_imparti' => (int)$request->temps_imparti,
            'ordre' => (int)$request->ordre,
            'est_actif' => (bool)$request->est_actif
        ]);

        $question->load(['theme', 'theme.phase']);

        return response()->json([
            'message' => 'Question créée avec succès',
            'question' => $question
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erreur lors de la création',
            'error' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Display the specified resource.
     */
    public function show(Request $request, Question $question)
    {
        $question->load(['theme', 'theme.phase', 'propositions']);
        return response()->json([
            'question' => $question
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        // Vérification admin
        $adminCheck = $this->checkAdmin($request->user());
        if ($adminCheck) return $adminCheck;

        $validator = Validator::make($request->all(), [
            'texte' => 'sometimes|required|string|max:1000',
            'explication' => 'nullable|string|max:500',
            'theme_id' => 'sometimes|required|exists:themes,id',
            'temps_imparti' => 'sometimes|required|integer|min:5|max:300',
            'ordre' => 'sometimes|required|integer',
            'est_actif' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $question->update($request->all());
        $question->load(['theme', 'theme.phase']);

        return response()->json([
            'message' => 'Question mise à jour avec succès',
            'question' => $question
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Question $question)
    {
        // Vérification admin
        $adminCheck = $this->checkAdmin($request->user());
        if ($adminCheck) return $adminCheck;

        // Vérifier si la question a des propositions associées
        if ($question->propositions()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer cette question car elle contient des propositions.'
            ], 422);
        }

        $question->delete();

        return response()->json([
            'message' => 'Question supprimée avec succès'
        ]);
    }

    /**
     * Récupérer les questions actives par thème (pour utilisateurs normaux)
     */
    public function questionsActivesParTheme($themeId)
    {
        $questions = Question::where('theme_id', $themeId)
                           ->where('est_actif', true)
                           ->with(['theme', 'theme.phase'])
                           ->orderBy('ordre', 'asc')
                           ->get(['id', 'texte', 'explication', 'theme_id', 'temps_imparti', 'ordre']);

        return response()->json([
            'questions' => $questions
        ]);
    }

    /**
     * Récupérer les questions actives par phase (pour utilisateurs normaux)
     */
    public function questionsActivesParPhase($phaseId)
    {
        $questions = Question::whereHas('theme', function($query) use ($phaseId) {
                            $query->where('phase_id', $phaseId);
                         })
                         ->where('est_actif', true)
                         ->with(['theme', 'theme.phase'])
                         ->orderBy('theme_id')
                         ->orderBy('ordre', 'asc')
                         ->get(['id', 'texte', 'explication', 'theme_id', 'temps_imparti', 'ordre']);

        return response()->json([
            'questions' => $questions
        ]);
    }

    /**
     * Récupérer une question avec ses propositions (pour le quiz)
     */
    public function questionAvecPropositions($questionId)
    {
        $question = Question::where('est_actif', true)
                          ->with(['propositions' => function($query) {
                              $query->where('est_actif', true)
                                    ->orderBy('ordre', 'asc');
                          }])
                          ->findOrFail($questionId);

        return response()->json([
            'question' => $question
        ]);
    }
}