<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportSession extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'status',
        'original_file',
    ];

    public function questions()
    {
        return $this->hasMany(ImportQuestion::class);
    }
}
