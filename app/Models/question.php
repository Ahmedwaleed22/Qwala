<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    use HasFactory;

    protected $table = 'questions_answers';

    protected $fillable = [
        'question',
        'answer',
        'topic_id'
    ];
}
