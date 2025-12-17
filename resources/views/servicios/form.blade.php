@if( isset($servicio) )
 {!! Form::open(['route' => ['servicio.update', $servicio->tokenx], 'method' => 'post']) !!}
@else
  {!! Form::open(['route' => 'servicio.store', 'method' => 'post']) !!}
@endif
  <div class="form-row">
    <div class="form-group col-md-10">
      {{ Form::label('Servicio', 'Servicio') }}
      {{ Form::text('servicio', ( isset($servicio))? $servicio->servicio : null, ['class' => 'form-control', 'placeholder' => 'Agua fría. Hielo, TWH, WHS', 'required']) }}
    </div>

    <div class="form-group col-md-2">
      {{ Form::label('Estado', 'estado') }}
      {{ Form::select('estado', [0 => 'Inactivo', 1 => 'Activo'],  (isset($servicio))? $servicio->estado : null, ['class' => 'form-control', 'required']) }}
    </div>

  </div>
  <div class="form-row">
    <div class="form-group col-md-2">
      @if( isset($servicio) )
        <button type="submit" class="btn btn-primary">Actualizar</button>
      @else
        <button type="submit" class="btn btn-primary">Crear</button>
      @endif
    </div>
  </div>
{!! Form::close() !!}
