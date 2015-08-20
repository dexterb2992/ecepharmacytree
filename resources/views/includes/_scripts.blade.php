<!-- jQuery 2.1.4 -->
{!! HTML::script('plugins/jQuery/jQuery-2.1.4.min.js') !!}
<!-- jQuery UI 1.11.4 -->
{!! HTML::script('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js') !!}
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script type="text/javascript">
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.2 JS -->
{!! HTML::script('bootstrap/js/bootstrap.min.js') !!}
<!-- Morris.js charts -->
{!! HTML::script('plugins/raphael/raphael-min.js') !!}
{!! HTML::script('plugins/morris/morris.min.js') !!}
<!-- Sparkline -->
{!! HTML::script('plugins/sparkline/jquery.sparkline.min.js') !!}
<!-- jvectormap -->
{!! HTML::script('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') !!}
{!! HTML::script('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') !!}
<!-- jQuery Knob Chart -->
{!! HTML::script('plugins/knob/jquery.knob.js') !!}
<!-- daterangepicker -->
{!! HTML::script('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js') !!}
{!! HTML::script('plugins/daterangepicker/daterangepicker.js') !!}
<!-- datepicker -->
{!! HTML::script('plugins/datepicker/bootstrap-datepicker.js') !!}
<!-- Bootstrap WYSIHTML5 -->
{!! HTML::script('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') !!}
<!-- Slimscroll -->
{!! HTML::script('plugins/slimScroll/jquery.slimscroll.min.js') !!}
<!-- FastClick -->
{!! HTML::script('plugins/fastclick/fastclick.min.js') !!}
<!-- AdminLTE App -->
{!! HTML::script('dist/js/app.min.js') !!}
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{!! HTML::script('dist/js/pages/dashboard.js') !!}
<!-- AdminLTE for demo purposes -->
{!! HTML::script('dist/js/demo.js') !!}