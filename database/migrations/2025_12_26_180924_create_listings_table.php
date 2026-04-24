<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            
            // Champs communs
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            
            $table->decimal('price', 15, 2);
            $table->string('currency')->default('GNF');
            
            $table->enum('type', ['residential', 'commercial', 'land', 'service']);
            $table->boolean('status')->default(false);
            $table->timestamp('published_at')->nullable();
            
            $table->json('images')->nullable();
            
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            
            $table->boolean('is_featured')->default(false);
            
            // Champs spécifiques (tous nullable)
            $table->integer('bedrooms')->nullable(); // Pour résidentiel
            $table->integer('bathrooms')->nullable(); // Pour résidentiel
            $table->integer('surface')->nullable(); // m² pour terrain/résidentiel
            $table->string('document_type')->nullable(); // Ex: Titre Foncier, Donation - pour Terrains
            $table->json('amenities')->nullable(); // Tags comme Piscine, Clim...
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
