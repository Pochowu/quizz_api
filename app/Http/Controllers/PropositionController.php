<?php

namespace App\Http\Controllers;

use App\Models\Proposition;
use Illuminate\Http\Request;

class PropositionController extends Controller
{
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

    public function propositionsActivesParQuestion($questionId)
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

   