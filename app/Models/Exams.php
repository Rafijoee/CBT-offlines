<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exams extends Model
{
    protected $fillable = [
        'mapel',
        'soal',
        'time',
        'opened_time',
        'closed_time',
        'token',
        'kelas',
    ];

    public function bankSoals()
    {
        return $this->hasMany(BankSoal::class, 'exams_id');
    }

    public function userExams()
    {
        return $this->hasMany(UserExam::class);
    }
}

