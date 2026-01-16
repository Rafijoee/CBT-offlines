<?php

namespace Database\Seeders;

use App\Models\BankSoal;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BankSoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BankSoal::create([
            'exams_id' => 1,
            'question_text' => 'What is the capital of France?',
            'gambar' => null,
        ]);
    }
}
