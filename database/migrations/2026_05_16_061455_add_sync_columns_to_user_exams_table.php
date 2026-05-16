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
        Schema::table('user_exams', function (Blueprint $table) {

            $table->uuid('exam_uuid')->nullable()->after('exam_id');

            $table->boolean('is_synced')->default(false);

            $table->timestamp('synced_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_exams', function (Blueprint $table) {
            //
        });
    }
};
