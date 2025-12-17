<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/waterhouse.css') }}">


    <title>Bienvenido a Sistema Call Center</title>
    <link href="{{ asset('img/favicon.ico') }}" rel="SHORTCUT ICON"/>

  </head>
  <body class="parallax">

    <div class="container ">
      <div class="row pt-5 mt-5">
        <div class="col-sm-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-body text-center">
              <h1>Ooops!</h1>
              @include('layouts.mensajes')
              <img src="{{ asset('img/dog.jpg') }}" class="mx-auto" alt="">
              <p class="pt-4">
                <a href="{{ route('home') }}" class="btn btn-lg btn-primary mr-4">Ir al inicio</a>
                <a href="{{ route('gr.logout') }}" class="btn btn-lg btn-danger">Cerrar sesión</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>


  </body>
</html>

@php
    Session::forget('success');
    Session::forget('error');
    Session::forget('warning');
    Session::forget('info');
    Session::forget('status');
@endphp
