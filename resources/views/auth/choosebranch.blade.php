@extends('auth._partials.template')

@section('title', 'Login');
@section('content')
<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>ECE </b>Pharmacy Tree</a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Select branch to continue</p>
            <div class="form-group has-feedback">
                <select class="form-control">
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8"></div><!-- /.col -->
                <div class="col-xs-4">
                    <button class="btn btn-primary btn-block btn-flat">Continue</button>
                </div><!-- /.col -->
            </div>

        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

@stop