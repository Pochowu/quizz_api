<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'texte',
        'est_correcte',
        'question_id',
        'ordre',
        'est_actif'
    ];

    protected $casts = [
        'est_correcte' => 'boolean',
        'ordre' => 'integer',
        'est_actif' => 'boolean'
    ];

    // Relation avec la question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Relation avec les réponses utilisateur
    // public function reponsesUtilisateur()
    // {
    //     return $this->hasMany(ReponseUtilisateur::class);
    // }
}