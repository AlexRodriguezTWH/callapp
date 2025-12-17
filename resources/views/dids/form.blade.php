@if( isset($did) )
 {!! Form::open(['route' => ['dids.update', $did->tokenx], 'method' => 'post']) !!}
@else
  {!! Form::open(['route' => 'dids.store', 'method' => 'post']) !!}
@endif
    <div class="form-group">
      {{ Form::label('nombre', 'Nombre') }}
      {{ Form::text('nombre', ( isset($did))? $did->nombre : null, ['class' => 'form-control', 'placeholder' => 'Juarez, Torreón, etc', 'required']) }}
    </div>

    <div class="form-group">
      {{ Form::label('did', 'DID') }}
      {{ Form::text('did', ( isset($did))? $did->did : null, ['class' => 'form-control', 'placeholder' => '1122334455', 'required']) }}
    </div>

    <div class="form-group">
      {{ Form::label('plaza', 'Plaza') }}
      {{ Form::text('plaza', ( isset($did))? $did->plaza : null, ['class' => 'form-control', 'placeholder' => 'JRZ, TRC', 'required']) }}
    </div>

    <div class="form-group">
      {{ Form::label('Estado', 'Estado') }}
      {{ Form::select('estado', [0 => 'Inactivo', 1 => 'Activo'],  (isset($did))? $did->estado : null, ['class' => 'form-control', 'required']) }}
    </div>

  <div class="form-row">
    <div class="form-group col-md-2">
      @if( isset($did) )
        <button type="submit" class="btn btn-primary">Actualizar</button>
      @else
        <button type="submit" class="btn btn-primary">Crear</button>
      @endif
    </div>
  </div>
{!! Form::close() !!}
