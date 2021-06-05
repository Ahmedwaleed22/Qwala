@extends('includes/master')
@section('content')
@if(session()->has('message'))
<div class="error">{{ session()->get('message') }}</div>
@endif
<div class="login-dark">
	<form action="/resetpassword" method="post">
		@csrf
		<h2 class="sr-only">Reset Password</h2>
		<div class="illustration">
			<i class="fas fa-fingerprint"></i>
		</div>
		<div class="form-group">
			<input class="form-control" type="email" name="email" placeholder="Email">
		</div>
		<div class="form-group"><button class="btn btn-primary btn-block" type="submit">Reset Password</button></div>
		<a href="/login" class="forgot">Login?</a>
		<a href="/register" class="forgot">Create New Account?</a>
	</form>
</div>
@stop