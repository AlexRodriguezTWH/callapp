@if( isset($tipoServicios) )
<table class="table table-striped">
  <thead>
    <tr>
      <th width="70%">Servicio</th>
      <th width="10%">Estado</th>
      <th width="20%">Control</th>
    </tr>
  </thead>
  @foreach($tipoServicios AS $servicio)
    <tr>
      <td> <b>{{ $servicio->servicio }}</b> </td>
      <td>
        @if($servicio->estado) <span style="color:green">Activo</span> @else <span style="color:red">Inactivo</span> @endif
      </td>
      <td>

        {!! Form::open(['route' => ['servicio.destroy', $servicio->tokenx], 'method' => 'delete']) !!}
          <a href="{{ route('servicio.editar',  $servicio->tokenx) }}" class="btn btn-sm btn-primary"> Editar</a>
          <button type="button" class="btn btn-sm btn-danger btnDeleteServicio"> Borrar</a>
        {!! Form::close() !!}

      </td>
    </tr>
  @endforeach
</table>
@else
  <h1>Aún no agregas algun tipo de servicio</h1>
@endif
