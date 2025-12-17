<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title> @yield('title', 'Control de tickets') </title>


  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('js/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
  <!--<link rel="stylesheet" href="{{ asset('js/plugins/toastr/toastr.min.css') }}"> -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

  <link rel="stylesheet" href="{{ asset('css/waterhouse.css') }}">

  <script>  window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};  </script>
  <script>  var __baseUrl = "{{url('/')}}"  </script>

</head>
<body class="control-sidebar-slide-open">
  <!-- Site wrapper -->
  <div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          @yield('section-title', 'Control de tickets')
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        @include('layouts.mensajes')
        @yield('content')
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->



  </div>
  <!-- ./wrapper -->


  <script src="{{ asset('js/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
  <script src="{{ asset('adminlte/js/demo.js') }}"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!--<script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  @yield('js_after')

</body>
</html>



@php
    Session::forget('success');
    Session::forget('error');
    Session::forget('warning');
    Session::forget('info');
    Session::forget('status');
@endphp
