<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    protected $table = 'bank_soals';

    protected $fillable = [
        'exams_id',
        'question_text',
        'gambar',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exams_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}

