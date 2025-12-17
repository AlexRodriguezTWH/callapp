@extends('layouts.backend.app')
@section('section-title')
  <h3>Pendientes</h3>
@endsection
@section('content')
      <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-12">
              <div class="card">

              <!-- PCECILIA -->
               <div class="card-header p-2 ">
                  @include('tickets.fragmentos.form-busqueda',  ['origen' => 'pendientes'])
                </div>

                  <div class="card-body" id="tlb-live">
                 @if( isset($llamadas) && $llamadas->count()>0 && $origen ='pendientes' )

                    @include('tickets.tabla-pendientes')
                    @if( isset($llamadas) && $llamadas->count()>0 )
                      {{ $llamadas->links() }}
                    @endif

                 @else
                    <h1>No hay llamadas encontradas</h1>
                    <a href="{{ route('tickets.pendientes') }}" class="btn btn-lg btn-success">Ver pendientes</a>
                  @endif
                  </div>
              </div>
          </div>
      </div>

@endsection
