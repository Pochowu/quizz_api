<?php

namespace Database\Seeders;

use App\Models\Phase;
use Illuminate\Database\Seeder;

class PhaseSeeder extends Seeder
{
    public function run()
    {
        $phases = [
            [
                'nom' => 'Phase 1 - Découverte',
                'description' => 'Phase initiale pour découvrir les bases',
                'points_par_question' => 1,
                'ordre' => 1,
                'est_active' => true
            ],
            [
                'nom' => 'Phase 2 - Intermédiaire',
                'description' => 'Phase intermédiaire pour approfondir les connaissances',
                'points_par_question' => 2,
                'ordre' => 2,
                'est_active' => true
            ],
            [
                'nom' => 'Phase 3 - Avancée',
                'description' => 'Phase avancée pour les experts',
                'points_par_question' => 3,
                'ordre' => 3,
                'est_active' => true
            ]
        ];

        foreach ($phases as $phaseData) {
            // Vérifier si la phase existe déjà par son nom
            if (!Phase::where('nom', $phaseData['nom'])->exists()) {
                Phase::create($phaseData);
                $this->command->info("Phase '{$phaseData['nom']}' créée.");
            } else {
                $this->command->info("Phase '{$phaseData['nom']}' existe déjà.");
            }
        }
    }
}