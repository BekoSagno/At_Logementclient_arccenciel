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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'new_listing', 'admin_message', 'message_response', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Données supplémentaires (ID de l'annonce, etc.)
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'read']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
