<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'user_exam_id',
        'bank_soal_id',
        'answer_id',
        'ragu',
        'score',
    ];

    protected $casts = [
        'ragu' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userExam()
    {
        return $this->belongsTo(UserExam::class);
    }

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}

