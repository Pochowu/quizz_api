<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les thèmes
        $themeHistoire = Theme::where('nom', 'Histoire')->first();
        $themeGeographie = Theme::where('nom', 'Géographie')->first();
        $themeSciences = Theme::where('nom', 'Sciences')->first();

        $questions = [
            // Thème Histoire
            [
                'texte' => 'En quelle année a eu lieu la Révolution française ?',
                'explication' => 'La Révolution française a débuté en 1789 avec la prise de la Bastille.',
                'theme_id' => $themeHistoire->id,
                'temps_imparti' => 30,
                'ordre' => 1,
                'est_actif' => true
            ],
            [
                'texte' => 'Qui était le premier président des États-Unis ?',
                'explication' => 'George Washington fut le premier président des États-Unis de 1789 à 1797.',
                'theme_id' => $themeHistoire->id,
                'temps_imparti' => 25,
                'ordre' => 2,
                'est_actif' => true
            ],
            // Thème Géographie
            [
                'texte' => 'Quel est le plus long fleuve du monde ?',
                'explication' => 'Le Nil est traditionnellement considéré comme le plus long fleuve du monde.',
                'theme_id' => $themeGeographie->id,
                'temps_imparti' => 20,
                'ordre' => 1,
                'est_actif' => true
            ],
            [
                'texte' => 'Quelle est la capitale du Japon ?',
                'explication' => 'Tokyo est la capitale du Japon depuis 1868.',
                'theme_id' => $themeGeographie->id,
                'temps_imparti' => 15,
                'ordre' => 2,
                'est_actif' => true
            ],
            // Thème Sciences
            [
                'texte' => 'Quelle est la formule chimique de l\'eau ?',
                'explication' => 'L\'eau est composée de deux atomes d\'hydrogène et un atome d\'oxygène.',
                'theme_id' => $themeSciences->id,
                'temps_imparti' => 20,
                'ordre' => 1,
                'est_actif' => true
            ],
            [
                'texte' => 'Combien de planètes compte notre système solaire ?',
                'explication' => 'Notre système solaire compte 8 planètes depuis que Pluton a été reclassée.',
                'theme_id' => $themeSciences->id,
                'temps_imparti' => 25,
                'ordre' => 2,
                'est_actif' => true
            ]
        ];

        foreach ($questions as $questionData) {
            if (!Question::where('texte', $questionData['texte'])->exists()) {
                Question::create($questionData);
                $this->command->info("Question créée : {$questionData['texte']}");
            } else {
                $this->command->info("Question existe déjà : {$questionData['texte']}");
            }
        }
    }
}