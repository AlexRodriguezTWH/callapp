@if( isset($falla) )
 {!! Form::open(['route' => ['fallas.update', $falla->tokenx], 'method' => 'post']) !!}
@else
  {!! Form::open(['route' => 'fallas.store', 'method' => 'post']) !!}
@endif

    <div class="form-group">
      {{ Form::label('tipo', 'Tipo') }}
      {{ Form::select('tipo', [0 => 'Reportado por cliente', 1 => 'Reportado por tecnico'],  (isset($falla))? $falla->tipo : null, ['class' => 'form-control', 'required']) }}
    </div>

    <div class="form-group">
      {{ Form::label('descripcion', 'Descripción') }}
      {{ Form::text('descripcion', ( isset($falla))? $falla->descripcion : null, ['class' => 'form-control', 'required']) }}
    </div>


    <div class="form-group">
      {{ Form::label('Estado', 'Estado') }}
      {{ Form::select('estado', [0 => 'Inactivo', 1 => 'Activo'],  (isset($falla))? $falla->estado : null, ['class' => 'form-control', 'required']) }}
    </div>

  <div class="form-row">
    <div class="form-group col-md-2">
      @if( isset($falla) )
        <button type="submit" class="btn btn-primary">Actualizar</button>
      @else
        <button type="submit" class="btn btn-primary">Crear</button>
      @endif
    </div>
  </div>
{!! Form::close() !!}
