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
    Schema::create('import_answers', function (Blueprint $table) {
        $table->id();

        $table->foreignId('import_question_id')
            ->constrained('import_questions')
            ->cascadeOnDelete();

        $table->char('option_key', 1); // A, B, C, D

        $table->text('answer_text')->nullable();

        $table->string('answer_image')->nullable();

        $table->boolean('is_true')->default(false);

        $table->timestamps();

        $table->unique(['import_question_id', 'option_key']);
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_answers');
    }
};
