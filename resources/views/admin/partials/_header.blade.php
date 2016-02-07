<header class="main-header">
  <!-- Logo -->
  <a href="{{ url('/') }}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>PT</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg glow">PharmacyTree</span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        @if(Auth::check())
        
          @include('admin.partials._user_menu')
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        @else
          <li class="dropdown user user-menu">
            <a href="{{ url('auth/login') }}" class="dropdown-toggle">
              Login
            </a>
          </li>
        @endif
      </ul>
    </div>
  </nav>
</header>
{!! Form::token() !!}