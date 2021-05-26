<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'participated_at',
        'topic_id'
    ];

    public function getCourse() {
    	return $this->belongsTo(course::class, 'course_id', 'id');
    }

    public function getTopic() {
    	return $this->belongsTo(topic::class, 'topic_id', 'id');
    }
}
