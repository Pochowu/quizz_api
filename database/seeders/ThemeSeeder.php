<?php

namespace Database\Seeders;

use App\Models\Theme;
use App\Models\Phase;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les phases
        $phase1 = Phase::where('ordre', 1)->first();
        $phase2 = Phase::where('ordre', 2)->first();
        $phase3 = Phase::where('ordre', 3)->first();

        $themes = [
            // Phase 1
            [
                'nom' => 'Histoire',
                'description' => 'Questions sur l\'histoire générale',
                'phase_id' => $phase1->id,
                'ordre' => 1,
                'est_actif' => true
            ],
            [
                'nom' => 'Géographie',
                'description' => 'Questions sur la géographie mondiale',
                'phase_id' => $phase1->id,
                'ordre' => 2,
                'est_actif' => true
            ],
            // Phase 2
            [
                'nom' => 'Sciences',
                'description' => 'Questions scientifiques avancées',
                'phase_id' => $phase2->id,
                'ordre' => 1,
                'est_actif' => true
            ],
            [
                'nom' => 'Littérature',
                'description' => 'Questions sur la littérature mondiale',
                'phase_id' => $phase2->id,
                'ordre' => 2,
                'est_actif' => true
            ],
            // Phase 3
            [
                'nom' => 'Technologie',
                'description' => 'Questions sur les nouvelles technologies',
                'phase_id' => $phase3->id,
                'ordre' => 1,
                'est_actif' => true
            ],
            [
                'nom' => 'Art et Culture',
                'description' => 'Questions sur l\'art et la culture',
                'phase_id' => $phase3->id,
                'ordre' => 2,
                'est_actif' => true
            ]
        ];

        foreach ($themes as $themeData) {
            if (!Theme::where('nom', $themeData['nom'])->exists()) {
                Theme::create($themeData);
                $this->command->info("Thème '{$themeData['nom']}' créé.");
            } else {
                $this->command->info("Thème '{$themeData['nom']}' existe déjà.");
            }
        }
    }
}