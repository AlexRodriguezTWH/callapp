@extends('layouts.backend.app')
@section('section-title')
  <h3>Fallas reportadas</h3>
  <a href="{{ route('fallas.create') }}" class="btn btn-sm btn-primary">Nuevo</a>
@endsection

@section('content')
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  @include('fallas.tabla')
                </div>
              </div>
          </div>
      </div>

@endsection

@section('js_after')
<script type="text/javascript" src="{{ asset('backend/js/fallas.js') }}" ></script>
@endsection
