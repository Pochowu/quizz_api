<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReponseUtilisateur extends Model
{
    use HasFactory;

    protected $table = 'reponse_utilisateurs';

    protected $fillable = [
        'user_id',
        'question_id',
        'proposition_id',
        'points_obtenus',
        'date_reponse'
    ];

    protected $casts = [
        'points_obtenus' => 'integer',
        'date_reponse' => 'datetime'
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec la question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Relation avec la proposition choisie
    public function proposition()
    {
        return $this->belongsTo(Proposition::class);
    }

    // Relation avec la phase via la question et le thème
    public function phase()
    {
        return $this->hasOneThrough(
            Phase::class,
            Question::class,
            'id', // Clé étrangère sur la table questions
            'id', // Clé étrangère sur la table phases
            'question_id', // Clé locale sur reponse_utilisateurs
            'theme_id' // Clé intermédiaire sur questions
        )->through('theme');
    }
}