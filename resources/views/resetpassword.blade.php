@extends('includes/master')
@section('content')
@if(session()->has('message'))
<div class="error">{{ session()->get('message') }}</div>
@endif
<div class="login-dark">
	<form action="/confirmresetpassword" method="post">
		@csrf
		<h2 class="sr-only">Reset Password Form</h2>
		<div class="illustration">
			<i class="fas fa-fingerprint"></i>
		</div>
		<input type="hidden" name="resettoken" value="{{ $token }}">
		<div class="form-group">
			<input class="form-control" type="password" name="password" placeholder="Password">
		</div>
		<div class="form-group"><button class="btn btn-primary btn-block" type="submit">Confirm</button></div>
	</form>
</div>
@stop