<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('propositions', function (Blueprint $table) {
            $table->id();
            $table->text('texte');
            $table->boolean('est_correcte')->default(false);
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->integer('ordre')->default(0);
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('propositions');
    }
};