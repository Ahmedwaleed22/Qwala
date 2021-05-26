@extends('includes/master')
@section('title', 'Qwala')
@section('content')
@include('includes/navbar')
@if(session()->has('message'))
<style>
    .error {
        margin-top: 78px;
    }
</style>
<div class="error">{{ session()->get('message') }}</div>
@endif
<div class="small-screens-only">You Can Get Your Quiz From <a href="quiz.php">Here</a></div>
<div class="homepage">
    <div class="container">
        <div class="right-section">
            <div class="groupid">
                <form action="/participate" method="post">
                    <select name="course" onchange="window.open('?course=' + this.value, '_self');">
                        <option value="" selected disabled>
                            @if (!$request->filled('course'))
                            Choose Course To Search
                            @else
                            {{ $request->course }}
                            @endif
                        </option>
                        @foreach ($allcourses as $course)
                        <option value="{{ $course->name }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                    @if ($user->group_code != null)
                    <h3 class="groupidtext">Your Current Group ID Is (Group ID)</h3>
                    @else
                    <form method="post" action="/">
                        <input name="groupid" type="number" min="100" max="5000" placeholder="Group ID" />
                        <button name="changegroupid" type="submit">Submit</button>
                    </form>
                    @endif
                </form>
            </div>
            @if (!$request->filled('course'))
            <table class="styled-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Enrollment Date</th>
                        <th scope="col">Course</th>
                        <th scope="col">Content</th>
                        <th class="big-screens-only" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="active-row">
                    @foreach($participated as $row)
                    @if(\Carbon\Carbon::parse($row->participated_at)->format('d/m/Y') >= date('d/m/Y', time()))
                    <tr>
                        <form method="post" action="/participate">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $row->getCourse->id }}" />
                            <input type="hidden" name="date" value="{{ date('d/m/Y', time()) }}" />
                            <input type="hidden" name="topic_id" value="0" />
                            <td><input name="enrollinbtn" onchange="this.form.submit()" type="checkbox" @if ($user->getCourse->count() > 0) checked @endif /></td>
                            <td>{{ \Carbon\Carbon::parse($row->participated_at)->format('d/m/Y') }}</td>
                            <td>{{ $row->getCourse->name }}</td>
                            <td><a href="/theoretical/questions/{{ $row->getTopic->id }}">{{ $row->getTopic->topic }}</a></td>
                            <td class="big-screens-only"><button onclick="alert('Coming Soon!')" class="btn danger" type="button">Get Quiz</button></td>
                        </form>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            @else
            <table class="styled-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date</th>
                        <th scope="col">Content</th>
                    </tr>
                </thead>
                <tbody class="active-row">
                    @foreach($search_results as $row)
                    <tr>
                        <form method="post" action="/participate">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $row->id }}" />
                            <td><input name="enrollinbtn" onchange="this.form.submit()" type="checkbox" @if ($user->getCourse->count() > 0) checked @endif /></td>
                            <td><input class="datechoose" name="date" type="date" value="22-4-2021" /></td>
                            <td>
                                <select style="outline: none" name="topic_id">
                                    @foreach($row->getTopics as $topic)
                                    <option value="{{ $topic->id }}">{{ $topic->topic }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            <h2>Previous Courses</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Enrollment Date</th>
                        <th scope="col">Course</th>
                        <th scope="col">Content</th>
                        <th class="big-screens-only" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="active-row">
                    @foreach($participated as $row)
                    @if(\Carbon\Carbon::parse($row->participated_at)->format('d/m/Y') < date('d/m/Y', time()))
                    <tr>
                        <form method="post" action="/participate">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $row->getCourse->id }}" />
                            <input type="hidden" name="date" value="{{ date('d/m/Y', time()) }}" />
                            <input type="hidden" name="topic_id" value="0" />
                            <td><input name="enrollinbtn" onchange="this.form.submit()" type="checkbox" @if ($user->getCourse->count() > 0) checked @endif /></td>
                            <td>{{ \Carbon\Carbon::parse($row->participated_at)->format('d/m/Y') }}</td>
                            <td>{{ $row->getCourse->name }}</td>
                            <td><a href="notes.php">{{ $row->getTopic->topic }}</a></td>
                            <td class="big-screens-only"><button onclick="alert('Coming Soon!')" class="btn danger" type="button">Get Quiz</button></td>
                        </form>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="left-section">
            <h2 class="title">Road Map</h2>
            @foreach($participated as $row)
            <div class="card">
                <h2>{{ $row->getCourse->name }}</h2>
                <ul>
                    @foreach($row->getCourse->getTopics as $topic)
                    <li>{{ $topic->topic }}
                        @if($row->getTopic->id  == $topic->id)
                        <i class="fas fa-hand-point-left"></i>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</div>
@stop