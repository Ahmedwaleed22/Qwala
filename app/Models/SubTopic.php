<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTopic extends Model
{
    use HasFactory;

    protected $table = 'subtopics';

    public function getQuestions() {
        return $this->hasMany('\App\Models\question', 'topic_id');
    }
}
