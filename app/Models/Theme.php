<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'phase_id',
        'ordre',
        'est_actif'
    ];

    protected $casts = [
        'ordre' => 'integer',
        'est_actif' => 'boolean'
    ];

    // Relation avec la phase
    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    // Relation avec les questions
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}