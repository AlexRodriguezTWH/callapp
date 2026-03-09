<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <!--<link rel="stylesheet" href="{{ asset('css/waterhouse.css') }}">-->
  <link rel="stylesheet" href="https://callcenter.thewaterhouse.com/css/waterhouse.css"> 


    <title>Bienvenido a Sistema Call Center</title>
    
    <link href="https://callcenter.thewaterhouse.com/img/favicon.ico" rel="SHORTCUT ICON" /> 

  </head>
  <body class="parallax">

<!--
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                  <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif -->

    <div class="container">
      @include('layouts.mensajes')
      <div class="row mt-5">
      <div class="col-sm-12 col-md-4 col-lg-4 p-5 bg-dark text-white" style="opacity: 0.7;">
          <h3>Call Center</h3>
          Sistema diseñado para seguimiento de llamadas.
      </div>
      <div class="col-sm-12 col-md-8 col-lg-8 p-5 bg-white">
        {!! Form::open(['method' => 'POST', 'role'=>'form', 'route'=>'gr.login' ]) !!}

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Usuario</label>
            <div class="col-sm-6">
              <input type="text" name="usuario" class="form-control" id="inputUsuario" autocomplete="off" value="">
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Contraseña</label>
            <div class="col-sm-6">
              <input type="password" name="pass" class="form-control" id="inputPassword" autocomplete="off" value="">
            </div>
          </div>
          <div class="form-group">
            <button class="btn btn-sm bg-wh text-white" type="submit" name="button">Enviar >></button>
          </div>

        {!! Form::close() !!}
      </div>
    </div>
    </div>







    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    -->
  </body>
</html>

@php
    Session::forget('success');
    Session::forget('error');
    Session::forget('warning');
    Session::forget('info');
    Session::forget('status');
@endphp
