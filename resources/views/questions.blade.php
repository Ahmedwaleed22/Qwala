@extends('includes/master')
@section('title', 'Qwala')
@section('content')
@include('includes/navbar')
<link rel="stylesheet" href="/css/notes.css" />
<div class="notes" id="notes">
	@foreach ($topic->getQuestions as $index=>$row)
		<div class="note">
			<div class="question">
				<span class="index">{{$index + 1}}.</span> {{ $row->question }}
			</div>
			<div class="answer">
				<div class="show-answer" data-answer="- {{ $row->answer }}" onclick="show_answer(event)">Show Answer</div>
			</div>
		</div>
	@endforeach
</div>

<script>
	var sheet = document.styleSheets[3];
	sheet.insertRule(":root{--name: '{{ $topic->topic }}'}");
</script>
@stop