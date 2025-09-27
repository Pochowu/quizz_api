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
        User::create([
            'email' => 'admin@quiz.com',
            'nom' => 'Administrateur',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'date_inscription' => Carbon::now()->toDateString(),
        ]);

        User::create([
            'email' => 'user@quiz.com',
            'nom' => 'Utilisateur Test',
            'password' => Hash::make('user123'),
            'is_admin' => false,
            'date_inscription' => Carbon::now()->toDateString(),
        ]);
    }
}