<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;

    protected $table = 'discussions';

    protected $fillable = [
        'user_id',
        'course_id',
        'topic_id',
        'link',
        'description',
        'start_time'
    ];

    public function getUser() {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    public function getCourse() {
        return $this->belongsTo('\App\Models\course', 'course_id');
    }

    public function getTopic() {
        return $this->belongsTo('\App\Models\topic', 'topic_id');
    }
}
