<?php

namespace App\Http\Controllers;

use App\Models\StatistiquesUtilisateur;
use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiquesUtilisateurController extends Controller
{
    /**
     * Récupérer les statistiques de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $statistiques = StatistiquesUtilisateur::where('user_id', $user->id)
                                             ->with('phase')
                                             ->get();

        // Statistiques globales
        $statistiquesGlobales = [
            'points_totaux' => $statistiques->sum('points_cumules'),
            'questions_totales_repondues' => $statistiques->sum('questions_repondues'),
            'bonnes_reponses_totales' => $statistiques->sum('bonnes_reponses'),
            'taux_reussite_global' => $statistiques->sum('questions_repondues') > 0 ? 
                ($statistiques->sum('bonnes_reponses') / $statistiques->sum('questions_repondues')) * 100 : 0
        ];

        return response()->json([
            'statistiques_par_phase' => $statistiques,
            'statistiques_globales' => $statistiquesGlobales
        ]);
    }

    /**
     * Récupérer les statistiques d'un utilisateur spécifique (admin)
     */
    public function show(Request $request, $userId)
    {
        // Vérification admin
        if (!$request->user()->is_admin) {
            return response()->json([
                'message' => 'Accès non autorisé. Admin requis.'
            ], 403);
        }

        $statistiques = StatistiquesUtilisateur::where('user_id', $userId)
                                             ->with(['user', 'phase'])
                                             ->get();

        $statistiquesGlobales = [
            'points_totaux' => $statistiques->sum('points_cumules'),
            'questions_totales_repondues' => $statistiques->sum('questions_repondues'),
            'bonnes_reponses_totales' => $statistiques->sum('bonnes_reponses'),
            'taux_reussite_global' => $statistiques->sum('questions_repondues') > 0 ? 
                ($statistiques->sum('bonnes_reponses') / $statistiques->sum('questions_repondues')) * 100 : 0
        ];

        return response()->json([
            'utilisateur' => $statistiques->first()->user ?? null,
            'statistiques_par_phase' => $statistiques,
            'statistiques_globales' => $statistiquesGlobales
        ]);
    }

    /**
     * Mettre à jour toutes les statistiques (admin)
     */
    public function mettreAJourToutesStatistiques(Request $request)
    {
        // Vérification admin
        if (!$request->user()->is_admin) {
            return response()->json([
                'message' => 'Accès non autorisé. Admin requis.'
            ], 403);
        }

        $usersUpdated = 0;

        // Pour chaque utilisateur, mettre à jour les statistiques de chaque phase
        $phases = Phase::all();
        
        foreach ($phases as $phase) {
            $users = \App\Models\User::has('reponsesUtilisateur')->get();
            
            foreach ($users as $user) {
                $statistique = StatistiquesUtilisateur::firstOrCreate([
                    'user_id' => $user->id,
                    'phase_id' => $phase->id
                ]);

                $statistique->mettreAJourStatistiques();
                $usersUpdated++;
            }
        }

        return response()->json([
            'message' => 'Statistiques mises à jour avec succès',
            'utilisateurs_mis_a_jour' => $usersUpdated
        ]);
    }

    /**
     * Classement des utilisateurs
     */
    public function classement(Request $request)
    {
        $classement = StatistiquesUtilisateur::select([
                                'user_id',
                                DB::raw('SUM(points_cumules) as total_points'),
                                DB::raw('SUM(questions_repondues) as total_questions'),
                                DB::raw('SUM(bonnes_reponses) as total_bonnes_reponses'),
                                DB::raw('CASE WHEN SUM(questions_repondues) > 0 THEN (SUM(bonnes_reponses) / SUM(questions_repondues)) * 100 ELSE 0 END as taux_reussite')
                            ])
                            ->groupBy('user_id')
                            ->with('user')
                            ->orderBy('total_points', 'desc')
                            ->orderBy('taux_reussite', 'desc')
                            ->get();

        return response()->json([
            'classement' => $classement
        ]);
    }

    /**
     * Classement par phase
     */
    public function classementParPhase(Request $request, $phaseId)
    {
        $classement = StatistiquesUtilisateur::where('phase_id', $phaseId)
                                           ->with('user')
                                           ->orderBy('points_cumules', 'desc')
                                           ->orderBy('taux_reussite', 'desc')
                                           ->get();

        return response()->json([
            'phase' => Phase::find($phaseId),
            'classement' => $classement
        ]);
    }
}