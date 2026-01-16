<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportAnswer extends Model
{
    protected $fillable = [
        'import_question_id',
        'option_key',
        'answer_text',
        'answer_image',
        'is_true'
    ];
}

