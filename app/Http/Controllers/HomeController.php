<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\enrollment;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\topic;

class HomeController extends Controller
{
    public function Index(Request $request) {
        $allcourses = course::all()->unique('name');
        $search_results = course::with('getTopics')->where(['name' => $request->course])->get();
        $user = Auth::user();
        $participatedIn = enrollment::where(['user_id' => $user->id])->get()->sortBy('participated_at');

        return view('index', [
            "allcourses" => $allcourses,
            "search_results" => $search_results,
            "request" => $request,
            "user" => $user,
            "participated" => $participatedIn,
        ]);
    }

    public function participate(Request $request) {
        $validation = Validator($request->all(), [
            'date' => ['date', 'nullable'],
            'course_id' => ['required', 'numeric'],
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        // If Empty Date Set To Today
        $date = empty($request->date) ? Carbon::now(new DateTimeZone('Africa/Cairo')) : Carbon::parse($request->date)->format('Y-m-d');

        $user_id = Auth::user()->id;
        $course_id = course::where('id', $request->course_id)->first()->id;
        $enrollmentCheck = enrollment::where([['user_id', $user_id], ['subtopic_id', topic::find($request->topic_id)->getSubTopics->first()->id]])->first();

        if ($request->to_previous) {
            $enrollment = enrollment::find($request->enrollment_id);
            $enrollment->participated_at = Carbon::now()->subDay();
            $enrollment->save();

            return redirect()->route("home")->with('message', 'You Successfully Marked This Topic As Done.');
        }

        if (is_numeric($request->course_id) && $course_id) {
            // Insert Enrollment Data
            $enrollmentData = $request->all();
            $enrollmentData['user_id'] = $user_id;
            $enrollmentData['course_id'] = $course_id;
            $enrollmentData['subtopic_id'] = topic::find($request->topic_id)->getSubTopics->first()->id;
            $enrollmentData['participated_at'] = $date;


            enrollment::create($enrollmentData);

            return redirect()->route("home")->with('message', 'You Successfully Participated In This Course, Have A Nice Journy!');
        }

        return back()->with('message', 'You Are Trying To Enroll To A Course That Doesn\'t Exist');
    }
}
