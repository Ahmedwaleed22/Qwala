<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\enrollment;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function Index(Request $request) {
        $allcourses = course::all()->unique('name');
        $search_results = course::where(['name' => $request->course])->get();
        $user = Auth::user();
        $participatedIn = enrollment::where(['user_id' => $user->id])->get();

        return view('index', [
            "allcourses" => $allcourses,
            "search_results" => $search_results,
            "request" => $request,
            "user" => $user,
            "participated" => $participatedIn,
        ]);
    }

    public function participate(Request $request) {
        $request->validate([
            'date' => ['date', 'nullable'],
            'topic_id' => ['required'],
        ]);

        // If Empty Date Set To Today
        $date = empty($request->date) ? Carbon::now(new DateTimeZone('Africa/Cairo')) : Carbon::parse($request->date)->format('Y-m-d');

        $user_id = Auth::user()->id;
        $course_id = course::where('id', $request->course_id)->first()->id;
        $enrollmentCheck = enrollment::where([['user_id', $user_id], ['course_id', $course_id]])->first();

        if ($enrollmentCheck) {
            $enrollmentCheck::destroy($enrollmentCheck->id);
            return redirect()->route("home")->with('message', 'You Successfully Un Participated From This Course.');
        }

        if (is_numeric($request->course_id) && $course_id) {
            // Insert Enrollment Data
            $enrollmentData = $request->all();
            $enrollmentData['user_id'] = $user_id;
            $enrollmentData['course_id'] = $course_id;
            $enrollmentData['participated_at'] = $date;

            enrollment::create($enrollmentData);

            return redirect()->route("home")->with('message', 'You Successfully Participated In This Course, Have A Nice Journy!');
        }

        return back()->with('message', 'You Are Trying To Enroll To A Course That Doesn\'t Exist');
    }
}
