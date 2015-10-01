<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>AdminLTE 2 | @yield('title')</title>
    @include('auth._partials._header')
  </head>
  @yield('content')

  @include('auth._partials._footer')
