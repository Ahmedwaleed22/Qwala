@extends('includes/master')
@section('title', 'Qwala - Discussion')
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
<link rel="stylesheet" href="/css/discussion.css" />
<div class="content">
	<div class="left-section">
		@foreach ($discussions as $discussion)
		<div class="item">
			<h2>{{ $discussion->getCourse->name }} <span class="subtopic">({{ $discussion->getTopic->topic }})</span></h2>
			<span class="author">By {{ $discussion->getUser->name }}</span>
			<a href="{{ $discussion->link }}">{{ $discussion->link }}</a>
			<p class="description">{{ $discussion->description }}</p>
			<div class="start_time"><span>Start Time:</span> {{ $discussion->start_time }}</div>
		</div>
		@endforeach
	</div>
	<div class="right-section">
		<div class="form">
			<h2 class="title">Create Discussion</h2>
			<form action="/discussion/create" method="post">
				@csrf
				<select name="mainTopic" required>
					<option value="" selected disabled>Main Topic</option>
					@foreach($courses as $course)
					<option value="{{ $course->id }}">{{ $course->name }}</option>
					@endforeach
				</select>
				<select name="subTopic" required>
					<option value="" selected disabled>Sub Topic</option>
					@foreach($topics as $topic)
					<option value="{{ $topic->id }}">{{ $topic->topic }}</option>
					@endforeach
				</select>
				<textarea name="description" placeholder="Description"></textarea>
				<input type="datetime-local" name="start_time">
				<input type="url" name="url" placeholder="Meeting URL" required>
				<button type="submit">Create Discussion</button>	
			</form>
		</div>
	</div>
</div>
@stop