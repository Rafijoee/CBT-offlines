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
        Schema::table('user_answers', function (Blueprint $table) {
            $table->foreignId('user_exam_id')
                ->after('user_id')
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropForeign(['user_exam_id']);
            $table->dropColumn('user_exam_id');
        });
    }
};
