<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('statistiques_utilisateur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('phase_id')->constrained()->onDelete('cascade');
            $table->integer('points_cumules')->default(0);
            $table->integer('questions_repondues')->default(0);
            $table->integer('bonnes_reponses')->default(0);
            $table->integer('questions_total')->default(0);
            $table->decimal('taux_reussite', 5, 2)->default(0);
            $table->timestamps();

            // Un utilisateur ne peut avoir qu'une seule statistique par phase
            $table->unique(['user_id', 'phase_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('statistiques_utilisateur');
    }
};