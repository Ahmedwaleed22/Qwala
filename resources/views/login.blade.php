@extends('includes/master')
@section('content')
@if(session()->has('message'))
<div class="error">{{ session()->get('message') }}</div>
@endif
<div class="login-dark">
	<form action="/login" method="post">
		@csrf
		<h2 class="sr-only">Login Form</h2>
		<div class="illustration">
			<i class="fal fa-lock-alt"></i>
		</div>
		<div class="form-group">
			<input class="form-control" type="text" name="name" placeholder="Username or Email">
		</div>
		<div class="form-group">
			<input class="form-control" type="password" name="password" placeholder="Password">
		</div>
		<div class="form-group"><button class="btn btn-primary btn-block" type="submit">Log In</button></div>
		<a href="/register" class="forgot">Create New Account?</a>
		<a href="/forgetpassword" class="forgot">Reset Your Password?</a>
	</form>
</div>
@stop