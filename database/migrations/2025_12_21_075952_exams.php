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
    Schema::create('exams', function (Blueprint $table) {
        $table->id();
        $table->string('mapel');
        $table->integer('soal');
        $table->integer('time'); // menit
        $table->timestamp('opened_time')->nullable(); 
        $table->timestamp('closed_time')->nullable(); 
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
