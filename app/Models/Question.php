<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'texte',
        'explication',
        'theme_id',
        'temps_imparti',
        'ordre',
        'est_actif'
    ];

    protected $casts = [
        'temps_imparti' => 'integer',
        'ordre' => 'integer',
        'est_actif' => 'boolean'
    ];

    // Relation avec le thème
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    // Relation avec les propositions
    // public function propositions()
    // {
    //     return $this->hasMany(Proposition::class);
    // }

    // // Relation avec les réponses utilisateur
    // public function reponsesUtilisateur()
    // {
    //     return $this->hasMany(ReponseUtilisateur::class);
    // }
}