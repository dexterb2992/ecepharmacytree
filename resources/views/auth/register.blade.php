@extends('auth._partials.template')

@section('content')
  <body class="register-page">
    <div class="register-box">
      <div class="register-logo">
        <a href="../../index2.html"><b>ECE </b>Pharmacy Tree</a>
      </div>

      <div class="register-box-body">
        <p class="login-box-msg">Register an account</p>
        {!! Form::open(['action' => 'Auth\AuthController@postRegister', 'method' => 'post', 'enctype' => "multipart/form-data"]) !!}
          <div class="form-group has-feedback">
            {!! Form::token() !!}
            {!! Form::text('fname', '', ['class' => 'form-control', 'placeholder' => 'First name']) !!}
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
            {!! _error($errors->first('fname')) !!}
          </div>

          <div class="form-group has-feedback">
            {!! Form::text('mname', '', ['class' => 'form-control', 'placeholder' => 'Middle name']) !!}
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
            {!! _error($errors->first('mname')) !!}
          </div>

          <div class="form-group has-feedback">
            {!! Form::text('lname', '', ['class' => 'form-control', 'placeholder' => 'Last name']) !!}
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
            {!! _error($errors->first('lname')) !!}
          </div>

          <div class="form-group has-feedback">
            {!! Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'Email']) !!}
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            {!! _error($errors->first('email')) !!}
          </div>

          <div class="form-group has-feedback">
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password (minimum of 6 characters)']) !!}
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            {!! _error($errors->first('password')) !!}
          </div>

          <div class="form-group has-feedback">
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Retype password']) !!}
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            {!! _error($errors->first('password_confirmation')) !!}
          </div>

          <div class="form-group">
            {!! Form::label('Role') !!}
            <select class="form-control" name="access_level">
              <!-- <option value="1" {{ !Auth::check() ? 'disabled="disabled"' : '' }}>Admin</option> -->
              <option value="2">Branch Manager</option>
            </select>
            {!! _error($errors->first('access_level')) !!}
          </div>

          <div class="form-group has-feedback">
            {!! Form::label('ECE Branch') !!}
            <?php 
              $arr_branches = [];
              foreach ($branches as $branch) {
                $arr_branches[$branch->id] = $branch->name;
              }
            ?>

            {!! Form::select('branch_id', $arr_branches, '', ["class" => "form-control"]) !!}
          </div>

          <div class="row">
            <div class="col-xs-8"></div>
            <div class="col-xs-4">
              {!! Form::submit('Register', ['class' => 'btn btn-primary btn-block btn-flat']) !!}
            </div><!-- /.col -->
          </div>
        {!! Form::close() !!}

        <a href="{{ url('/auth/login') }}" class="text-center">I already have an account</a>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->
@stop