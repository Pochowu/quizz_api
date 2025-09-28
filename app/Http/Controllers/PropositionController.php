<?php

namespace App\Http\Controllers;

use App\Models\Proposition;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropositionController extends Controller
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

        $propositions = Proposition::with('question')
                                 ->orderBy('question_id')
                                 ->orderBy('ordre')
                                 ->get();
        
        return response()->json([
            'propositions' => $propositions
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

        $validator = Validator::make($request->all(), [
            'texte' => 'required|string|max:500',
            'est_correcte' => 'required|boolean',
            'question_id' => 'required|exists:questions,id',
            'ordre' => 'required|integer',
            'est_actif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $proposition = Proposition::create($request->all());
        $proposition->load('question');

        return response()->json([
            'message' => 'Proposition créée avec succès',
            'proposition' => $proposition
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Proposition $proposition)
    {
        $proposition->load('question');
        return response()->json([
            'proposition' => $proposition
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proposition $proposition)
    {
        // Vérification admin
        $adminCheck = $this->checkAdmin($request->user());
        if ($adminCheck) return $adminCheck;

        $validator = Validator::make($request->all(), [
            'texte' => 'sometimes|required|string|max:500',
            'est_correcte' => 'sometimes|required|boolean',
            'question_id' => 'sometimes|required|exists:questions,id',
            'ordre' => 'sometimes|required|integer',
            'est_actif' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $proposition->update($request->all());
        $proposition->load('question');

        return response()->json([
            'message' => 'Proposition mise à jour avec succès',
            'proposition' => $proposition
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Proposition $proposition)
    {
        // Vérification admin
        $adminCheck = $this->checkAdmin($request->user());
        if ($adminCheck) return $adminCheck;

        // Vérifier si la proposition a des réponses utilisateur associées
        if ($proposition->reponsesUtilisateur()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer cette proposition car elle a des réponses associées.'
            ], 422);
        }

        $proposition->delete();

        return response()->json([
            'message' => 'Proposition supprimée avec succès'
        ]);
    }

    /**
     * Récupérer les propositions actives par question (pour utilisateurs normaux)
     */
    public function propositionsActivesParQuestion($questionId)
    {
        $propositions = Proposition::where('question_id', $questionId)
                                 ->where('est_actif', true)
                                 ->with('question')
                                 ->orderBy('ordre', 'asc')
                                 ->get(['id', 'texte', 'question_id', 'ordre']);

        return response()->json([
            'propositions' => $propositions
        ]);
    }

    /**
     * Récupérer toutes les propositions pour une question (sans indication de réponse correcte)
     */
    public function propositionsPourQuestion($questionId)
    {
        $propositions = Proposition::where('question_id', $questionId)
                                 ->where('est_actif', true)
                                 ->orderBy('ordre', 'asc')
                                 ->get(['id', 'texte', 'ordre']);

        return response()->json([
            'propositions' => $propositions
        ]);
    }
}