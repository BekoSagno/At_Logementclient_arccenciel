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
        Schema::table('listings', function (Blueprint $table) {
            $table->string('service_status')->nullable()->after('type'); // recherche/propose/réalisé
            $table->json('custom_fields')->nullable()->after('amenities'); // 3 champs max avec titre et valeur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['service_status', 'custom_fields']);
        });
    }
};
