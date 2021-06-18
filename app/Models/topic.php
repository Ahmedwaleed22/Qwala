<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class topic extends Model
{
    use HasFactory;

    public function getSubTopics() {
        return $this->hasMany('\App\Models\SubTopic', 'topic_id');
    }
}