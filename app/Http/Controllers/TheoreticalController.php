<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubTopic;

class TheoreticalController extends Controller
{
    public function questions($topic_id) {
        $topic = SubTopic::findOrFail($topic_id);

        return view('questions', [
            "topic" => $topic
        ]);
    }
}
