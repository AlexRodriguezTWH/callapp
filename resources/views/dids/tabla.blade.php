@if( isset($dids) )
<table class="table table-striped">
  <thead>
    <tr>
      <th width="50%">Nombre</th>
      <th width="10%">DID</th>
      <th width="10%">Plaza</th>
      <th width="10%">Estado</th>
      <th width="20%">Control</th>
    </tr>
  </thead>
  @foreach($dids AS $did)
    <tr>
      <td> <b>{{ $did->nombre }}</b> </td>
      <td> {{ $did->did }} </td>
      <td> {{ $did->plaza }} </td>
      <td>
        @if($did->estado) <span style="color:green">Activo</span> @else <span style="color:red">Inactivo</span> @endif
      </td>
      <td>

        {!! Form::open(['route' => ['dids.destroy', $did->tokenx], 'method' => 'delete']) !!}
          <a href="{{ route('dids.editar',  $did->tokenx) }}" class="btn btn-sm btn-primary"> Editar</a>
          <button type="button" class="btn btn-sm btn-danger btnDeleteDID"> Borrar</a>
        {!! Form::close() !!}

      </td>
    </tr>
  @endforeach
</table>
@else
  <h1>Aún no agregas algun tipo de servicio</h1>
@endif
