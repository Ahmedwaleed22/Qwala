@extends('includes/master')
@section('content')

@if(session()->has('errors'))
@foreach($errors->all() as $error)
<div class="error">{{ $error }}</div>
@endforeach
@endif
<div class="login-dark">
	<form action="/register" method="post">
		@csrf
		<h2 class="sr-only">Register Form</h2>
		<div class="illustration">
			<i class="fas fa-user"></i>
		</div>
		<div class="form-group">
			<input class="form-control" type="text" name="name" placeholder="Username">
		</div>
		<div class="form-group">
			<input class="form-control" type="email" name="email" placeholder="Email">
		</div>
		<div class="form-group">
			<input class="form-control" type="password" name="password" placeholder="Password">
		</div>
		<div class="form-group"><button class="btn btn-primary btn-block" type="submit">Register</button></div>
		<a href="/login" class="forgot">Already Have An Account?</a>
	</form>
</div>
@stop