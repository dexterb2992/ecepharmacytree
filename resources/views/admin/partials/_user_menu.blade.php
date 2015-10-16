<li class="dropdown user user-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    {!! HTML::image( url('images/128x128/'.Auth::user()->photo), "", ["class"=>"user-image", "alt" => "User Image"]) !!}
    <span class="hidden-xs">{{ Auth::user()->fname." ".Auth::user()->lname }}</span>
  </a>
  
        
  <ul class="dropdown-menu">
    <!-- User image -->
    <li class="user-header">
      {!! HTML::image( url('images/128x128/'.Auth::user()->photo), "", ["class"=>"img-circle"]) !!}
      <p>
        {{ Auth::user()->fname." ".Auth::user()->lname }} - {{ get_role(Auth::user()->access_level) }}
        <small>Member {{ \Carbon\Carbon::parse(Auth::user()->created_at)->diffForHumans() }}</small>
      </p>
    </li>
    <!-- Menu Body -->
    <li class="user-body">
      <div class="pull-left">
        <a href="{{ route('profile') }}" class="btn btn-default btn-flat">Profile</a>
      </div>
      <div class="pull-right">
        <a href="/auth/logout" class="btn btn-default btn-flat">Sign out</a>
      </div>
    </li>
  </ul>
</li>