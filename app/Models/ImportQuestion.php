<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportQuestion extends Model
{
    protected $fillable = [
        'import_session_id',
        'question_text',
        'question_image',
        'score',
        'type',
        'order_no'
    ];

    public function answers()
    {
        return $this->hasMany(ImportAnswer::class);
    }
}

