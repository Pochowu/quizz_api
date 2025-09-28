<?php

namespace Database\Seeders;

use App\Models\Proposition;
use App\Models\Question;
use Illuminate\Database\Seeder;

class PropositionSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les questions
        $questionHistoire1 = Question::where('texte', 'En quelle année a eu lieu la Révolution française ?')->first();
        $questionHistoire2 = Question::where('texte', 'Qui était le premier président des États-Unis ?')->first();
        $questionGeo1 = Question::where('texte', 'Quel est le plus long fleuve du monde ?')->first();
        $questionGeo2 = Question::where('texte', 'Quelle est la capitale du Japon ?')->first();

        $propositions = [
            // Question Histoire 1
            [
                'texte' => '1789',
                'est_correcte' => true,
                'question_id' => $questionHistoire1->id,
                'ordre' => 1,
                'est_actif' => true
            ],
            [
                'texte' => '1799',
                'est_correcte' => false,
                'question_id' => $questionHistoire1->id,
                'ordre' => 2,
                'est_actif' => true
            ],
            [
                'texte' => '1776',
                'est_correcte' => false,
                'question_id' => $questionHistoire1->id,
                'ordre' => 3,
                'est_actif' => true
            ],
            [
                'texte' => '1815',
                'est_correcte' => false,
                'question_id' => $questionHistoire1->id,
                'ordre' => 4,
                'est_actif' => true
            ],

            // Question Histoire 2
            [
                'texte' => 'George Washington',
                'est_correcte' => true,
                'question_id' => $questionHistoire2->id,
                'ordre' => 1,
                'est_actif' => true
            ],
            [
                'texte' => 'Thomas Jefferson',
                'est_correcte' => false,
                'question_id' => $questionHistoire2->id,
                'ordre' => 2,
                'est_actif' => true
            ],
            [
                'texte' => 'Abraham Lincoln',
                'est_correcte' => false,
                'question_id' => $questionHistoire2->id,
                'ordre' => 3,
                'est_actif' => true
            ],
            [
                'texte' => 'Benjamin Franklin',
                'est_correcte' => false,
                'question_id' => $questionHistoire2->id,
                'ordre' => 4,
                'est_actif' => true
            ],

            // Question Géographie 1
            [
                'texte' => 'Le Nil',
                'est_correcte' => true,
                'question_id' => $questionGeo1->id,
                'ordre' => 1,
                'est_actif' => true
            ],
            [
                'texte' => 'L\'Amazone',
                'est_correcte' => false,
                'question_id' => $questionGeo1->id,
                'ordre' => 2,
                'est_actif' => true
            ],
            [
                'texte' => 'Le Mississippi',
                'est_correcte' => false,
                'question_id' => $questionGeo1->id,
                'ordre' => 3,
                'est_actif' => true
            ],
            [
                'texte' => 'Le Yangtsé',
                'est_correcte' => false,
                'question_id' => $questionGeo1->id,
                'ordre' => 4,
                'est_actif' => true
            ],

            // Question Géographie 2
            [
                'texte' => 'Tokyo',
                'est_correcte' => true,
                'question_id' => $questionGeo2->id,
                'ordre' => 1,
                'est_actif' => true
            ],
            [
                'texte' => 'Kyoto',
                'est_correcte' => false,
                'question_id' => $questionGeo2->id,
                'ordre' => 2,
                'est_actif' => true
            ],
            [
                'texte' => 'Osaka',
                'est_correcte' => false,
                'question_id' => $questionGeo2->id,
                'ordre' => 3,
                'est_actif' => true
            ],
            [
                'texte' => 'Nagoya',
                'est_correcte' => false,
                'question_id' => $questionGeo2->id,
                'ordre' => 4,
                'est_actif' => true
            ]
        ];

        foreach ($propositions as $propositionData) {
            if (!Proposition::where('texte', $propositionData['texte'])
                           ->where('question_id', $propositionData['question_id'])
                           ->exists()) {
                Proposition::create($propositionData);
                $this->command->info("Proposition créée : {$propositionData['texte']}");
            } else {
                $this->command->info("Proposition existe déjà : {$propositionData['texte']}");
            }
        }
    }
}