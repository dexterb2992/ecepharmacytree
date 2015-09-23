<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
     <title>ECE Pharmacy Tree - <?php echo isset($title) ? $title : ''?>@yield('title') </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="{{{ asset('img/favicon.png') }}}">
    
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
              @yield('content')
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->
        @include('admin.partials._footer')
    </div><!-- ./wrapper -->
    
    <script type="text/javascript">
      /* Add your custom javascript functions here */
      
      function str_singular(str){
        var res = '<?php echo str_singular("'+str+'");?>';
        return res;
      }

      function str_plural(str){
        var lastChar = "", replacement = "", new_str;

        // lastChar = substr(str, strlen( str ) - 2);
        // new_str = substr(str, 0, strlen( str ) - 2);

        lastChar = str.substr(str.length-2);
        new_str = str.substr(0, str.length-2);


        if( lastChar == "um" ) replacement = "a";
        if( lastChar == "fe" ) replacement = "ves";
        if( lastChar == "us" ) replacement = "i";
        if( lastChar == "ch" )  return str+"es";

        if( replacement != "" ) return new_str+replacement;



        // lastChar = substr(str, strlen(str) -1 );
        lastChar = str.substr(str.length-1);
        // new_str = substr(str, 0, strlen( str ) - 1);
        new_str = str.substr(0, str.length-1);

        if( lastChar == "f" ) replacement = "ves";

        if( lastChar == "y" ) replacement = "ies";
        
          // return new_str+replacement;

        if( lastChar == "s" || lastChar == "x" ){
          return str+"es";
        }else{
          return str+"s";
        }


        if( replacement == "" ){
          new_str = str;
        }
        
        return new_str+replacement;
      } 

      function str_auto_plural(str, quantity){
        if( quantity > 1 ){
          return str_plural( str );
        }
        return str_singular( str );
      }
    </script>
    @include('includes._scripts')

    @yield('scripts')
  </body>
</html>