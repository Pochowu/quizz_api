<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Vérifier si l'admin existe déjà
        if (!User::where('email', 'admin@quiz.com')->exists()) {
            User::create([
                'email' => 'admin@quiz.com',
                'nom' => 'Administrateur',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'date_inscription' => Carbon::now()->toDateString(),
            ]);
            $this->command->info('Utilisateur admin créé.');
        } else {
            $this->command->info('Utilisateur admin existe déjà.');
        }

        // Vérifier si l'utilisateur test existe déjà
        if (!User::where('email', 'user@quiz.com')->exists()) {
            User::create([
                'email' => 'user@quiz.com',
                'nom' => 'Utilisateur Test',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'date_inscription' => Carbon::now()->toDateString(),
            ]);
            $this->command->info('Utilisateur test créé.');
        } else {
            $this->command->info('Utilisateur test existe déjà.');
        }
    }
}