<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer ou mettre à jour l'utilisateur admin principal
        User::updateOrCreate(
            ['email' => 'amedbekosagno@arccenciel.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('amed@2025.'),
                'email_verified_at' => now(),
            ]
        );
        
        // Garder l'ancien admin si différent (pour compatibilité)
        $oldAdmin = User::where('email', 'admin@at-logement.com')
            ->where('email', '!=', 'amedbekosagno@arccenciel.com')
            ->first();
        
        if (!$oldAdmin) {
            User::firstOrCreate(
                ['email' => 'admin@at-logement.com'],
                [
                    'name' => 'Administrateur',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}

