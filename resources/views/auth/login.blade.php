@extends('auth._partials.template')

@section('title', 'Login');
@section('content')
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="{{ url('/') }}"><b>ECE </b>Pharmacy Tree</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        {!! Form::open(['action' => 'Auth\AuthController@postLogin', 'method' => 'post']) !!}
          <div class="form-group has-feedback">
            {!! Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'Email', 'required' => 'required']) !!}
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'required' => 'required']) !!}
            {!! Form::token() !!}
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            {!! _error($errors->first('email')) !!}
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember" value="true"> Remember Me
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              {!! Form::submit('Sign In', ['class' => 'btn btn-primary btn-block btn-flat']) !!}
            </div><!-- /.col -->
          </div>
        {!! Form::close() !!}

        <a href="/password/email">I forgot my password</a><br>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

@stop
