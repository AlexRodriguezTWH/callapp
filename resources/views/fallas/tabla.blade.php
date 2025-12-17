@if( isset($fallas) )
<table class="table table-striped">
  <thead>
    <tr>
      <th width="20%">Tipo</th>
      <th width="50%">Descripción</th>
      <th width="10%">Estado</th>
      <th width="20%">Control</th>
    </tr>
  </thead>
  @foreach($fallas AS $falla)
    <tr>
      <td>
        @if($falla->tipo == 0 ) <b>Reportada por cliente</b> @else <b>Reportado por tenico</b> @endif
      </td>
      <td> {{ $falla->descripcion }} </td>
      <td>
        @if($falla->estado) <span style="color:green">Activo</span> @else <span style="color:red">Inactivo</span> @endif
      </td>
      <td>

        {!! Form::open(['route' => ['fallas.destroy', $falla->tokenx], 'method' => 'delete']) !!}
          <a href="{{ route('fallas.editar',  $falla->tokenx) }}" class="btn btn-sm btn-primary"> Editar</a>
          <button type="button" class="btn btn-sm btn-danger btnDeleteFalla"> Borrar</a>
        {!! Form::close() !!}

      </td>
    </tr>
  @endforeach
</table>
@else
  <h1>Aún no agregas algún item</h1>
@endif
