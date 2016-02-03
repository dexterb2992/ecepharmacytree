@extends('auth._partials.template')

@section('title', 'Login');
@section('content')
<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}">Pharmacy Tree</a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Select branch to continue</p>
            {!! Form::open(['action' => 'UserController@setBranchToLogin', 'method' => 'post']) !!}
                <div class="form-group has-feedback">
                    <select class="form-control" name="branch_id">
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-xs-2"></div><!-- /.col -->
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary pull-right btn-flat" style="margin-left: 6px;">Continue</button>  
                        <a href="/auth/logout" class="btn btn-warning pull-right btn-flat">Cancel</a>
                    </div><!-- /.col -->
                </div>
            {!! Form::close() !!}
        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

@stop