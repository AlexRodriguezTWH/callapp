@if( isset($motivo) )
 {!! Form::open(['route' => ['motivos.update', $motivo->tokenx], 'method' => 'post']) !!}
@else
  {!! Form::open(['route' => 'motivos.store', 'method' => 'post']) !!}
@endif
    <div class="form-group">
      {{ Form::label('descripcion', 'Descripción') }}
      {{ Form::text('descripcion', ( isset($motivo))? $motivo->descripcion : null, ['class' => 'form-control', 'placeholder' => 'Fuga,
Vandalismo', 'required']) }}
    </div>

    <div class="form-group">
      {{ Form::label('usuarioAlta', 'Usuario') }}
      {{ Form::text('usuarioAlta', ( isset($motivo))? $motivo->usuarioAlta : null, ['class' => 'form-control', 'placeholder' => 'Marisolg', 'required']) }}
    </div>

    <div class="form-group">
      {{ Form::label('Estado', 'Estado') }}
      {{ Form::select('estado', [0 => 'Inactivo', 1 => 'Activo'],  (isset($motivo))? $motivo->estado : null, ['class' => 'form-control', 'required']) }}
    </div>

  <div class="form-row">
    <div class="form-group col-md-2">
      @if( isset($motivo) )
        <button type="submit" class="btn btn-primary">Actualizar</button>
      @else
        <button type="submit" class="btn btn-primary">Crear</button>
      @endif
    </div>
  </div>
{!! Form::close() !!}
