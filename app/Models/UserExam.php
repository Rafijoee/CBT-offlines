<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExam extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'soal_array',
    ];

    protected $casts = [
        'soal_array' => 'array', // JSON → array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exams::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function resultExam()
    {
        return $this->hasOne(ResultExam::class);
    }
}
