<!DOCTYPE html>
<html>
  	<head>
	    <meta charset="UTF-8">
	    <title>ECEPharmacyTree | @yield('title')</title>
	    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
	    @include('auth._partials._header')
  	</head>
  	@if(Session::has("flash_message"))
      <div class="alert-success alert-{{ Session::get('flash_message')['type'] }} alert">
        @if(Session::get('flash_message')["type"] == "important")
          <button class="close" data-dismiss="alert" aria-hidden="true" type="button">&times;</button>
        @endif
        {{ Session::get("flash_message")["msg"] }}
      </div>
    @endif
  	@yield('content')

	@if(Session::has("flash_message"))
      <script type="text/javascript">
        $("div.alert").not(".alert-important").delay(5000).slideUp(function(){
          $(this).remove();
        });
      </script>
    @endif
  @include('auth._partials._footer')

