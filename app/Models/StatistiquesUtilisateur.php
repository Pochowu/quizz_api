<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistiquesUtilisateur extends Model
{
    use HasFactory;

    protected $table = 'statistiques_utilisateur';

    protected $fillable = [
        'user_id',
        'phase_id',
        'points_cumules',
        'questions_repondues',
        'bonnes_reponses',
        'questions_total',
        'taux_reussite'
    ];

    protected $casts = [
        'points_cumules' => 'integer',
        'questions_repondues' => 'integer',
        'bonnes_reponses' => 'integer',
        'questions_total' => 'integer',
        'taux_reussite' => 'decimal:2'
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec la phase
    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    // Méthode pour mettre à jour les statistiques
    public function mettreAJourStatistiques()
    {
        $reponses = ReponseUtilisateur::where('user_id', $this->user_id)
                                    ->whereHas('question.theme', function($query) {
                                        $query->where('phase_id', $this->phase_id);
                                    })
                                    ->with('proposition')
                                    ->get();

        $this->questions_repondues = $reponses->count();
        $this->bonnes_reponses = $reponses->where('proposition.est_correcte', true)->count();
        $this->points_cumules = $reponses->sum('points_obtenus');
        
        // Calcul du taux de réussite
        if ($this->questions_repondues > 0) {
            $this->taux_reussite = ($this->bonnes_reponses / $this->questions_repondues) * 100;
        } else {
            $this->taux_reussite = 0;
        }

        // Nombre total de questions dans la phase
        $this->questions_total = Question::whereHas('theme', function($query) {
                                        $query->where('phase_id', $this->phase_id);
                                    })
                                    ->where('est_actif', true)
                                    ->count();

        $this->save();
    }
}