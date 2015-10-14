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

    <!-- jQuery 2.1.4 -->
    <script src="../../plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
@stop