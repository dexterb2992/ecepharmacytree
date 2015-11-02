@extends('auth._partials.template')
@section('title', 'Reset Password')
@section('content')
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="/"><b>ECE </b>Marketing</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Reset your password</p>
        {!! Form::open(['action' => 'Auth\PasswordController@postEmail', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
          <div class="form-group has-feedback">
            {!! Form::token() !!}
            {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email']) !!}
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            {!! _error($errors->first('email')) !!}
          </div>
          <div class="row">
            <div class="col-xs-12">
              {!! Form::submit('Send Password Reset Link', ['class' => 'btn btn-primary btn-block btn-flat']) !!}
            </div><!-- /.col -->
          </div>
        {!! Form::close() !!}

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop