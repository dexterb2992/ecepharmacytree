<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
     <title>App Name - @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    @include('includes._styles')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue sidebar-mini">
    <div class="wrapper">
        @include('admin.partials._header');
        @include('admin.partials._sidebar');

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Dashboard
                <small>Control panel</small>
              </h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i></a></li>
                <li class="active">Dashboard</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              @yield('content')
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->
        @include('admin.partials._footer')
    </div><!-- ./wrapper -->

    @include('includes._scripts')

    @yield('scripts')
  </body>
</html>