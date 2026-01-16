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
    Schema::create('import_questions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('import_session_id')
            ->constrained('import_sessions')
            ->cascadeOnDelete();

        $table->text('question_text');

        $table->string('question_image')->nullable();

        $table->integer('score')->default(1);

        $table->enum('type', ['PG', 'ESSAY'])->default('PG');

        $table->integer('order_no');

        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_questions');
    }
};
