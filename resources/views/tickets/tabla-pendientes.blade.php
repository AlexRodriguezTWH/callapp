@if(  isset($llamadas) && $llamadas->count() > 0   )

  <table class="table table-sm table-striped text-sm">
    <thead class="bg-wh text-white">
      <tr>
        <th>ID</th>
        <th>Fecha</th>
        <th>Ext</th>
        <th>Plaza</th>
        <th>Ruta</th>
        <th>Pto Venta.</th>
        <th>Código Id.</th>
        <th>T. Servicio</th>
        <th>Estado</th>
        <th>Motivo <br> LLamada</th>
        <th class="col-4">Observaciones</th>
        <th>Usuario</th>
      </tr>
    </thead>
    <tbody>
      @foreach($llamadas AS $call)
        @php
          $plaza = null;
          if(isset($call->Plaza)){
                                    $plaza = $call->Plaza()->where('TipoAlmacen', 1)
                                    ->where('EstadoLogico', 1)
                                    ->whereNotIn('IdCompania', [52,51])
                                    ->whereNotIn('idalmacen', ['TRCSLW','MTYSLW','LSLSLW'])
                                    ->first();
          }
        @endphp
        <tr id="row-{{ $call->ID }}">
          <td>
            <a href="#" class="btn btn-sm btn-success"
                data-toggle   = "modal"
                data-target   = "#modalTicketEnvivo"
                data-caller   = "{{ $call->CallerID }}"
                data-fecha    = "{{ $call->Fechatms }}"
                data-ext      = "{{ $call->IdExt }}"
                data-duracion = "{{ $call->Duracion }}"
                data-ID    = "{{ $call->ID }}">
                {{ $call->ID }}
            </a>
          </td>
          <td>
            <small> {{  str_replace(".000", "", $call->Fechatms) }} <br>
              <b>
                {{ $call->CallerID }} -
                {{ ( isset($call->Duracion) && $call->Duracion > 0 )? $call->Duracion : 0 }} segs
              </b>
            </small>
          </td>
          <td> {{ $call->IdExt }}  </td>
          <td>
            @if( isset($call->Plaza))
              {{ isset(  $plaza->NombreAlmacen   )? $plaza->NombreAlmacen : "SP" }}
            @endif
          </td>
          <td>{{ $call->Ruta }}</td>
          <td>{{ (isset($call->PV))? $call->PV->NombreAlmacen : "SN" }}</td>
          <td>{{ $call->CodigoIdentificacion }}</td>
          <td>{{ ( isset($call->Servicio) )? $call->Servicio->Descripcion : "-" }}</td>
          <td>{{ ( isset($call->StatusTick) )? $call->StatusTick->Descrip : "-" }}</td>
          <td>{{ ( isset($call->MotivoLlamada) )? $call->MotivoLlamada->Descripcion : "-" }}</td>
          <td>
            <a href="#"
                data-toggle   = "modal"
                data-target   = "#modalTicketEnvivo"
                data-caller   = "{{ $call->CallerID }}"
                data-fecha    = "{{ $call->Fechatms }}"
                data-ext      = "{{ $call->IdExt }}"
                data-duracion = "{{ $call->Duracion }}"
                data-ID    = "{{ $call->ID }}">
            {{ $call->Observaciones }}
          </a>
        </td>
        <td>
          <b>{{ $call->userid }}</b>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

@else

  <h1 class="text-muted">Sin registros</h1>

@endif

@include('modals.ticket')

@section('js_after')
  <script src="{{ asset('backend/js/ticket.js') }}"></script>
@endsection
