<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer l'utilisateur admin
        $this->call(AdminUserSeeder::class);
        
        // Créer des annonces de démonstration
        $this->call(ListingSeeder::class);
        
        // Créer des messages de démonstration
        $this->call(MessageSeeder::class);
    }
}
