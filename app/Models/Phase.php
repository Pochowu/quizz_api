<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'points_par_question',
        'ordre',
        'est_active'
    ];

    protected $casts = [
        'points_par_question' => 'integer',
        'ordre' => 'integer',
        'est_active' => 'boolean'
    ];

    // Relation avec les thèmes
    public function themes()
    {
        return $this->hasMany(Theme::class);
    }

    // // Relation avec les statistiques utilisateur
    public function statistiquesUtilisateur()
    {
        return $this->hasMany(StatistiquesUtilisateur::class);
     }
}