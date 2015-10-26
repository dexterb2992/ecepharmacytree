<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ECEPharmacyTree - <?php echo isset($title) ? $title : ''?> | @yield('title') </title>
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
    <body class="skin-blue fixed sidebar-mini">
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
                        <small>Control panel</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i></a></li>
                        <li class="active">{{ isset($title) ? $title : '' }}</li>
                    </ol>
                </section>
          
                <!-- Main content -->
                <section class="content">
                    @if(Auth::check())
                    <p><span class="text-muted">You're logged in at Branch: </span>
                        <span class="text-aqua text-bold">
                            <?php $branches_count = Branch::all()->count(); ?>
                            @if( Auth::check() && Auth::user()->isAdmin() && $branches_count > 1)
                                @if( Session::has('selected_branch') )
                                    {{ Branch::find(Session::get('selected_branch'))->name }}
                                @else
                                    {!! Session::put('selected_branch', Auth::user()->branch->id) !!}
                                    {{ Branch::find(Session::get('selected_branch'))->name }}
                                @endif
                            @else
                                {{ Auth::check() ? Auth::user()->branch->name : '' }}
                            @endif
                        </span>
                    </p>
                    @endif
                    @if(Session::has("flash_message"))
                    <div class="alert-success alert alert-{{ Session::get('flash_message')['type'] }} alert">
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
            $("div.alert").not(".alert-important").delay(5000).slideUp(function(){
              $(this).remove();
          });
        </script>
        @endif
        {!! HTML::script('dist/fn.helpers.js') !!}
        @include('includes._scripts')

        @yield('scripts')
    </body>
</html>