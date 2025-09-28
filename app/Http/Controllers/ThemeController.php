<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
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

        $themes = Theme::with('phase')->orderBy('phase_id')->orderBy('ordre')->get();
        
        return response()->json([
            'themes' => $themes
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
            'phase_id' => 'required|exists:phases,id',
            'ordre' => 'required|integer',
            'est_actif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $theme = Theme::create($request->all());
        $theme->load('phase');

        return response()->json([
            'message' => 'Thème créé avec succès',
            'theme' => $theme
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Theme $theme)
    {
        $theme->load('phase');
        return response()->json([
            'theme' => $theme
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Theme $theme)
    {
        // Vérification admin
        $adminCheck = $this->checkAdmin($request->user());
        if ($adminCheck) return $adminCheck;

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'phase_id' => 'sometimes|required|exists:phases,id',
            'ordre' => 'sometimes|required|integer',
            'est_actif' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $theme->update($request->all());
        $theme->load('phase');

        return response()->json([
            'message' => 'Thème mis à jour avec succès',
            'theme' => $theme
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Theme $theme)
    {
        // Vérification admin
        $adminCheck = $this->checkAdmin($request->user());
        if ($adminCheck) return $adminCheck;

        // Vérifier si le thème a des questions associées
        if ($theme->questions()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer ce thème car il contient des questions.'
            ], 422);
        }

        $theme->delete();

        return response()->json([
            'message' => 'Thème supprimé avec succès'
        ]);
    }

    /**
     * Récupérer les thèmes actifs par phase (pour utilisateurs normaux)
     */
    public function themesActifsParPhase($phaseId)
    {
        $themes = Theme::where('phase_id', $phaseId)
                      ->where('est_actif', true)
                      ->with('phase')
                      ->orderBy('ordre', 'asc')
                      ->get(['id', 'nom', 'description', 'phase_id', 'ordre']);

        return response()->json([
            'themes' => $themes
        ]);
    }

    /**
     * Récupérer tous les thèmes actifs (pour utilisateurs normaux)
     */
    public function themesActifs()
    {
        $themes = Theme::where('est_actif', true)
                      ->with('phase')
                      ->orderBy('phase_id')
                      ->orderBy('ordre', 'asc')
                      ->get(['id', 'nom', 'description', 'phase_id', 'ordre']);

        return response()->json([
            'themes' => $themes
        ]);
    }

    /**
     * Récupérer tous les thèmes avec pagination (pour utilisateurs normaux)
     */
    public function allThemes(Request $request)
    {
        $themes = Theme::with('phase')
                      ->where('est_actif', true)
                      ->orderBy('phase_id')
                      ->orderBy('ordre', 'asc')
                      ->get(['id', 'nom', 'description', 'phase_id', 'ordre']);

        return response()->json([
            'themes' => $themes
        ]);
    }
}