<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\topic;

class TheoreticalController extends Controller
{
    public function questions($topic_id) {
        $topic = topic::findOrFail($topic_id);

        return view('questions', [
            "topic" => $topic
        ]);
    }
}
