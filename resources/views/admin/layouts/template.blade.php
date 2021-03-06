<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PharmacyTree - <?php echo isset($title) ? $title : ''?>  @yield('title') </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <?php use ECEPharmacyTree\Branch; ?>
    @include('includes._styles')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-green fixed sidebar-mini">
        <div class="wrapper">
            <!-- jQuery 2.1.4 -->
            {!! HTML::script('plugins/jQuery/jQuery-2.1.4.min.js') !!}
            
            @include('admin.partials._header');

            @include('admin.partials._sidebar');

            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        {{ isset($title) ? $title : '' }}
                        @if(Auth::check())<small>Control panel</small>@endif
                    </h1>
                    @if(Auth::check())
                    <ol class="breadcrumb">
                        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i></a></li>
                        <li class="active">{{ isset($title) ? $title : '' }}</li>
                    </ol>
                    @endif
                </section>
          
                <!-- Main content -->
                <section class="content">
                    @if(Auth::check())
                    <p><span class="text-muted">You're logged in at Branch: </span>
                        <span class="text-aqua text-bold">
                            <?php $branches_count = Branch::all()->count(); ?>
                            
                            @if( Auth::check() && Auth::user()->isAdmin() && $branches_count > 1)
                                <span id="session_branch_name"></span>                         
                                <small><i>[<a href="{{ url('change-branch') }}">Change</a>]</i></small>
                            @elseif( Auth::check() && !Auth::user()->isAdmin())
                                <?php session()->put('selected_branch', Auth::user()->branch->id); ?>
                                <span id="session_branch_name">{{ Auth::user()->branch->name }}</span>

                            @endif
                        </span>
                    </p>
                    @endif
                    @if(Session::has("flash_message"))
                    <div class="alert alert-{{ Session::get('flash_message')['type'] }}">
                        @if(Session::get('flash_message')["type"] == "important")
                        <button class="close" data-dismiss="alert" aria-hidden="true" type="button">&times;</button>
                        @endif
                        {{ Session::get("flash_message")["msg"] }}
                    </div>
                    @endif
                    @yield('content')
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            @include('admin.partials._footer')
        </div><!-- ./wrapper -->

        @if(Session::has("flash_message"))
        <script type="text/javascript">
            $("div.alert").not(".alert-important").delay(8000).slideUp(function(){
                $(this).remove();
            });
        </script>
        @endif
        {!! HTML::script('dist/fn.helpers.js') !!}
        @include('includes._scripts')

        @yield('scripts')
    </body>
</html>