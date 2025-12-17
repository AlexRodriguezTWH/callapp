@if(  isset($llamadas) && $llamadas->count() > 0   )

  <table class="table table-sm table-striped">
    <thead class="bg-wh text-white">
      <tr>
        <th width="5%">ID</th>
        <th width="5%">Ext</th>
        <th width="10%">Caller ID</th>
        <th width="15%">Fecha</th>
        <th width="5%">Duración</th>
        <th width="10%">Plaza</th>
        <th >Control</th>
      </tr>
    </thead>
    <tbody>
      @foreach($llamadas AS $call)
        <tr id="row-{{ $call->ID }}">
          <td>{{ $call->ID }}</td>
          <td>{{ $call->IdExt }}</td>
          <td>{{ $call->CallerID }}</td>
          <td>{{ $call->Fechatms }}</td>
          <td>{{ $call->Duracion }}</td>
          <td>{{ $call->IdPlaza }}</td>
          <td>
            <a data-href="{{ route('ticket.view', $call->ID) }}" class="btnOpenEditar">
              <img src= "{{ asset('img/btnEditar.png') }}" />
            </a>
          </td>
        </tr>

      @endforeach
    </tbody>
  </table>

@else
  <h1 class="text-muted">Sin registros</h1>
@endif
