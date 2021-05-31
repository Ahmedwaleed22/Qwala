<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\course;
use App\Models\topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiscussionController extends Controller
{
    public function index() {
        Discussion::where('start_time', '<=', Carbon::now())->delete();
        $discussions = Discussion::all();
        $courses = course::all();
        $topics = topic::all();

        return view('discussion', [
            'discussions' => $discussions,
            'courses' => $courses,
            'topics' => $topics
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'mainTopic' => ['numeric', 'required'],
            'subTopic' => ['numeric', 'required'],
            'url' => ['url', 'required'],
            'description' => ['string', 'required'],
            'start_time' => ['required']
        ]);

        $twoDaysLater = Carbon::now()->addDays(2);

        if ($request->start_time > $twoDaysLater) {
            return back()->with('message', 'You can\'t add discusstion for more than 2 days later');
        } else if ($request->start_time < Carbon::now()) {
            return back()->with('message', 'You can\'t add discusstion with a past time');
        }

        $discussion = Discussion::create([
            'user_id' => Auth::user()->id,
            'course_id' => $request->mainTopic,
            'topic_id' => $request->subTopic,
            'link' => $request->url,
            'description' => $request->description,
            'start_time' => Carbon::parse($request->start_time)->format('Y-m-d\TH:i')
        ]);

        return redirect()->route('discussion')->with('message', 'Discussion Added Successfully.');
    }
}
