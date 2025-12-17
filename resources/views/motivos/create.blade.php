@extends('layouts.backend.app')
@section('section-title')
  <h3>Nuevo Motivo de llamada</h3>
@endsection

@section('content')
      <div class="row">
          <div class="col-md-9 offset-md-1">
              <div class="card">
                <div class="card-body">
                  @include('motivos.form')
                </div>
              </div>
          </div>
      </div>

@endsection
