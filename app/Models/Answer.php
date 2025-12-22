<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'bank_soal_id',
        'text',
        'gambar',
        'true',
    ];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}

