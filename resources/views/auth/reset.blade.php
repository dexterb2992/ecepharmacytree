@extends('auth._partials.template')
<?php $email = validate_reminder_token($token); ?>
@if( $email !== false)
	@section('Reset Password')
	@section('content')
		@if(count($errors) > 0)
			@foreach($errors as $error)
				{!! _error($error) !!}
			@endforeach
		@endif
		<body class="login-page">
		    <div class="login-box">
		      <div class="login-logo">
		        <a href="{{ url('/') }}">Pharmacy Tree</a>
		      </div><!-- /.login-logo -->
		      <div class="login-box-body">
		        <p class="login-box-msg">Reset your password</p>
		        {!! Form::open(['action' => 'Auth\PasswordController@postReset', 'method' => 'post']) !!}
		          <div class="form-group has-feedback">
		          	{!! Form::hidden('email', $email) !!}
		            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter new password']) !!}
		            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
		            {!! _error($errors->first('password')) !!}
		          </div>
		          <div class="form-group has-feedback">
		            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm new password']) !!}
		            {!! Form::hidden('token', $token) !!}
		            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
		            {!! _error($errors->first('password_confirmation')) !!}
		          </div>
		          <div class="row">
		          	<div class="col-xs-6">
		              <a href="{{ url('auth/login') }}" class="btn btn-block btn-warning btn-flat">Cancel</a>
		            </div><!-- /.col -->
		            <div class="col-xs-6">	
		              {!! Form::submit('Update password', ['class' => 'btn btn-primary btn-block btn-flat btn-block']) !!}
		            </div>
		          </div>
		        {!! Form::close() !!}

		      </div><!-- /.login-box-body -->
		    </div><!-- /.login-box -->
	@stop
@else

	@section('Invalid Reset Link')
	@section('content')
		<body class="login-page">
		    <div class="login-box">
		      <div class="login-logo">
		        <a href="{{ url('/') }}"><b>ECE </b>Pharmacy Tree</a>
		      </div><!-- /.login-logo -->
		      <div class="login-box-body">
		        <p class="login-box-msg">Reset your password</p>
		       	{!! _error('The link may have been expired or have been removed. Please request for a new Password Reset Link 
				<a href="'.url('password/email').'">here</a>.', 'alert') !!}
				@stop

		      </div><!-- /.login-box-body -->
		    </div><!-- /.login-box -->
		
@endif
