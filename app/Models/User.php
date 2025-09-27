<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'nom',
        'password',
        'date_inscription',
        'is_admin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_inscription' => 'date',
        'is_admin' => 'boolean'
    ];

    public function reponsesUtilisateur()
    {
        return $this->hasMany(ReponseUtilisateur::class);
    }

    public function statistiques()
    {
        return $this->hasMany(StatistiquesUtilisateur::class);
    }
}