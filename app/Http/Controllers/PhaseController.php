<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhaseController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Vérification admin
        $adminCheck = $this->checkAdmin($request->user());
        if ($adminCheck) return $adminCheck;

        $phases = Phase::orderBy('ordre', 'asc')->get();
        
        return response()->json([
            'phases' => $phases
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
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_par_question' => 'required|integer|min:1',
            'ordre' => 'required|integer',
            'est_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $phase = Phase::create($request->all());

        return response()->json([
            'message' => 'Phase créée avec succès',
            'phase' => $phase
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Phase $phase)
    {
        // Accessible à tous les utilisateurs authentifiés
        return response()->json([
            'phase' => $phase
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phase $phase)
    {
        // Vérification admin
        $adminCheck = $this->checkAdmin($request->user());
        if ($adminCheck) return $adminCheck;

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'points_par_question' => 'sometimes|required|integer|min:1',
            'ordre' => 'sometimes|required|integer',
            'est_active' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $phase->update($request->all());

        return response()->json([
            'message' => 'Phase mise à jour avec succès',
            'phase' => $phase
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Phase $phase)
    {
        // Vérification admin
        $adminCheck = $this->checkAdmin($request->user());
        if ($adminCheck) return $adminCheck;

        // Vérifier si la phase a des thèmes associés
        if ($phase->themes()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer cette phase car elle contient des thèmes.'
            ], 422);
        }

        $phase->delete();

        return response()->json([
            'message' => 'Phase supprimée avec succès'
        ]);
    }

    /**
     * Récupérer les phases actives pour les utilisateurs normaux
     */
    public function phasesActives()
    {
        // Accessible à tous les utilisateurs authentifiés
        $phases = Phase::where('est_active', true)
                      ->orderBy('ordre', 'asc')
                      ->get(['id', 'nom', 'description', 'points_par_question']);

        return response()->json([
            'phases' => $phases
        ]);
    }
}