<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield("title")</title>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin_asset/dist/css/adminlte.min.css')}}">
  {{-- notification using toastr css--}}
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/css/toastr.min.css')}}">

   <link rel="icon" href="{{asset('admin_asset/dist/img/favicon.ico') }}" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css" />
     <!-- pace-progress -->
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/pace-progress/themes/blue/pace-theme-center-radar.css')}}">
  {{-- PAGE CSS --}}
  @section("page_css")

  @show
  {{-- PAGE CSS END --}}
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed accent-info sidebar-collapse">
<div class="wrapper">
  <!-- Navbar -->
  @include('admin.layout.nav-bar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('admin.layout.main-sidebar')

  <!-- Content Wrapper. Contains page content -->
  @section("body")

  @show
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="https://www.buglercoaches.co.uk/" target="_blank">Bugler Coaches Ltd</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('admin_asset/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{ asset('admin_asset/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('admin_asset/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin_asset/dist/js/adminlte.js')}}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{ asset('admin_asset/dist/js/demo.js')}}"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="{{ asset('admin_asset/plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
<script src="{{ asset('admin_asset/plugins/raphael/raphael.min.js')}}"></script>
<script src="{{ asset('admin_asset/plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
<script src="{{ asset('admin_asset/plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
{{-- prograce bar --}}
<script src="{{asset('admin_asset/plugins/pace-progress/pace.min.js')}}"></script>
{{-- prograce bar end --}}
<!-- ChartJS -->
{{-- <script src="{{ asset('admin_asset/plugins/chart.js/Chart.min.js')}}"></script> --}}

<!-- PAGE SCRIPTS -->
<!--<script src="{{ asset('admin_asset/dist/js/pages/dashboard2.js')}}"></script>-->







@section('page_script')
@show

{{-- notification using toastr --}}
<script src="{{ asset('admin_asset/js/toastr.min.js')}}"></script>

{{-- pop-up notification --}}
<script type="text/javascript">
  // start ajex request progress bar-
  $(document).ajaxStart(function() { Pace.restart(); });
  // end ajex request progress bar-


  @if(session()->has('success'))
    toastr.success("{{session()->get('success')}}")
  @endif

  @if(session()->has('error'))
    toastr.error("{{session()->get('error_get')}}")
  @endif

  @if(session()->has('info'))
    toastr.warning("{{session()->get('info')}}")
  @endif
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
    // $(function () {
    //     $('#reporting_time').datetimepicker(
    //       {
    //         format:'DD-MM-YYYY HH:mm',
    //       }
    //     );
    //     $('#ending_time').datetimepicker(
    //       {
    //         format:'DD-MM-YYYY HH:mm',
    //       }
    //     );
    // });

/*        $(function () {
        $('#Trip_Start_Date').datetimepicker(
          {
            format:'DD-MM-YYYY HH:mm',
          }
        );
        $('#return_date').datetimepicker(
          {
            format:'DD-MM-YYYY HH:mm',
          }
        );
    });*/
</script>

</body>
</html>
