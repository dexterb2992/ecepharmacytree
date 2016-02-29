@extends('admin.layouts.template')
@section('title', '500 Oops! Something went wrong.')
@section('content')
  <div class="error-page">
    <h2 class="headline text-red">500</h2>
    <div class="error-content">
      <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>
      <p>
        We will work on fixing that right away.
        Meanwhile, you may <a href="{{url('/')}}">return to dashboard</a> or try using the search form.
      </p>
      <form class="search-form" action="{{ route('product_search') }}">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search for products" />
          <div class="input-group-btn">
            <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
          </div>
        </div><!-- /.input-group -->
      </form>
    </div>
  </div><!-- /.error-page -->

@stop