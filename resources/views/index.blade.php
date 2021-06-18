@extends('includes/master')
@section('title', 'Qwala')
@section('content')
@include('includes/navbar')
<style>
    .error {
        margin-top: 78px;
    }
</style>
@if(session()->has('message'))
<div class="error">{{ session()->get('message') }}</div>
@endif
@foreach ($errors->all() as $error)
<div class="error">{{ $error }}</div>
@endforeach
<div class="small-screens-only">You Can Get Your Quiz From <a href="quiz.php">Here</a></div>
<div class="homepage">
    <div class="container">
        <div class="right-section">
            <div class="groupid">
                <form action="/participate" method="post">
                    <select name="course" onchange="window.open('?course=' + this.value, '_self');">
                        <option value="" selected disabled>
                            @if (!$request->filled('course'))
                            Course
                            @else
                            {{ $request->course }}
                            @endif
                        </option>
                        @foreach ($allcourses as $course)
                        <option value="{{ $course->name }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            @if (!$request->filled('course'))
            <table class="styled-table">
                <thead>
                    <tr>
                        <th scope="col">Done</th>
                        <th scope="col">Date</th>
                        <th scope="col">Topic</th>
                        <th scope="col">Sub-Topic</th>
                        <th class="big-screens-only" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="active-row">
                    @foreach($participated as $row)
                    @if(\Carbon\Carbon::parse($row->participated_at)->format('d/m/Y') >= date('d/m/Y', time()))
                    <tr>
                        <form method="post" action="/participate">
                            @csrf
                            <input type="hidden" name="to_previous" value="true">
                            <input type="hidden" name="course_id" value="{{ $row->getCourse->id }}" />
                            <input type="hidden" name="subtopic_id" value="0" />
                            <input type="hidden" name="topic_id" value="{{ $row->topic_id }}">
                            <input type="hidden" name="enrollment_id" value="{{ $row->id }}">
                            <td><input name="enrollinbtn" onchange="this.form.submit()" type="checkbox" @if (!$user->getCourse->count() > 0) checked @endif /></td>
                            <td>{{ \Carbon\Carbon::parse($row->participated_at)->format('d/m/Y') }}</td>
                            <td>{{ $row->getCourse->getTopics->find($row->topic_id)->topic }}</td>
                            <td><a href="/theoretical/questions/{{ $row->getCourse->getTopics->find($row->topic_id)->getSubTopics->find($row->subtopic_id)['id'] }}">{{ $row->getCourse->getTopics->find($row->topic_id)->getSubTopics->find($row->subtopic_id)['topic'] }}</a></td>
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
                        <th scope="col">Enroll</th>
                        <th scope="col">Date</th>
                        <th scope="col">Topic</th>
                    </tr>
                </thead>
                <tbody class="active-row">
                    @foreach($search_results as $row)
                    <tr>
                        <form method="post" action="/participate">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $row->id }}" />
                            <td><input name="enrollinbtn" onchange="this.form.submit()" type="checkbox"/></td>
                            <td><input class="datechoose" name="date" type="date" value="0" /></td>
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
                        <th scope="col">Date</th>
                        <th scope="col">Topic</th>
                        <th scope="col">Sub-Topic</th>
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
                            <input type="hidden" name="subtopic_id" value="0" />
                            <input type="hidden" name="topic_id" value="{{ $row->topic_id }}">
                            <td>{{ \Carbon\Carbon::parse($row->participated_at)->addDay()->format('d/m/Y') }}</td>
                            <td>{{ $row->getCourse->getTopics->find($row->topic_id)->topic }}</td>
                            <td><a href="/theoretical/questions/{{ $row->getCourse->getTopics->find($row->topic_id)->getSubTopics->find($row->subtopic_id)['id'] }}">{{ $row->getCourse->getTopics->find($row->topic_id)->getSubTopics->find($row->subtopic_id)['topic'] }}</a></td>
                            <td class="big-screens-only"><button class="btn info" type="submit">Re Enroll</button></td>
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
            @if(\Carbon\Carbon::parse($row->participated_at)->format('d/m/Y') >= date('d/m/Y', time()))
            <div class="card">
                <h2>{{ $row->getCourse->name }}</h2>
                <ul>
                    @foreach($row->getCourse->getTopics as $topic)
                    <li>
                        {{ $topic->topic }}
                        <ul class="subtopic">
                            @foreach($topic->getSubTopics as $subtopic)
                            <a href="{{ $subtopic->link }}">
                                <li>
                                    {{ $subtopic->topic }}
                                    @if($row->subtopic_id  == $subtopic->id)
                                        <i class="fas fa-hand-point-left"></i>
                                    @endif
                                </li>
                            </a>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@stop