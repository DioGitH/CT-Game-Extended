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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['multiple_choice', 'true_false', 'essay']);
            $table->text('description')->nullable();
            $table->text('question_text')->nullable();
            $table->string('question_image')->nullable();
            $table->integer('score'); // Score for the question
            $table->integer('exp'); // Exp for the question
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
