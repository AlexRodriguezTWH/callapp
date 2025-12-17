@if( isset($motivos) )
<table class="table table-striped">
  <thead>
    <tr>
      <th width="50%">Descripción</th>
      <th width="10%">Usuario</th>
      <th width="10%">Estado</th>
      <th width="20%">Control</th>
    </tr>
  </thead>
  @foreach($motivos AS $motivo)
    <tr>
      <td> <b>{{ $motivo->descripcion }}</b> </td>
      <td> {{ $motivo->usuarioAlta }} </td>
      <td>
        @if($motivo->estado) <span style="color:green">Activo</span> @else <span style="color:red">Inactivo</span> @endif
      </td>
      <td>

        {!! Form::open(['route' => ['motivos.destroy', $motivo->tokenx], 'method' => 'delete']) !!}
          <a href="{{ route('motivos.editar',  $motivo->tokenx) }}" class="btn btn-sm btn-primary"> Editar</a>
          <button type="button" class="btn btn-sm btn-danger btnDeleteMotivo"> Borrar</a>
        {!! Form::close() !!}

      </td>
    </tr>
  @endforeach
</table>
@else
  <h1>Aún no agregas algún item</h1>
@endif
