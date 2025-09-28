<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reponse_utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('proposition_id')->constrained()->onDelete('cascade');
            $table->integer('points_obtenus')->default(0);
            $table->timestamp('date_reponse')->useCurrent();
            $table->timestamps();

            // Un utilisateur ne peut répondre qu'une fois à une question
            $table->unique(['user_id', 'question_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reponse_utilisateurs');
    }
};