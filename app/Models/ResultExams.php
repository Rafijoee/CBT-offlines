<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultExam extends Model
{
    protected $table = 'results_exams';

    protected $fillable = [
        'user_id',
        'user_exam_id',
        'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userExam()
    {
        return $this->belongsTo(UserExam::class);
    }
}

