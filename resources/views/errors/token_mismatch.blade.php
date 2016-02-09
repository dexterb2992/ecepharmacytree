@extends('admin.layouts.template')
@section('title', '551 Token Mismatch.')
@section('content')
  <div class="error-page">
    <h2 class="headline text-yellow"> 551</h2>
    <div class="error-content">
      <h3><i class="fa fa-warning text-yellow"></i> Oops! Token Mismatch.</h3>
      <p>
        In order to protect you from potential attackers, your session token has expired.
        Meanwhile, you may  go <a href="{{URL::previous()}}">back</a> to the previous page to renew your session token.
      </p>
      <form class="search-form" action="{{ route('product_search') }}">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search for products" />
          <div class="input-group-btn">
            <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
          </div>
        </div><!-- /.input-group -->
      </form>
    </div><!-- /.error-content -->
  </div><!-- /.error-page -->

@stop