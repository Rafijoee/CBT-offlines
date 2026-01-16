<?php

namespace Database\Seeders;

use App\Models\Exams;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Exams::create([
            'mapel' => 'Matematika',
            'soal' => 30,
            'time' => 60,
            'opened_time' => now(),
        ]);
    }
}
