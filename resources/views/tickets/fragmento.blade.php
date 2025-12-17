<tr id="row-{{ $call->ID }}">
  <td>{{ $call->ID }}</td>
  <td>{{ $call->IdExt }}</td>
  <td>{{ $call->CallerID }}</td>
  <td>{{ $call->Fechatms }}</td>
  <td>{{ $call->Duracion }}</td>
  <td>{{ $call->IdPlaza }}</td>
  <td>
    <a href="#" class = ""
                data-toggle   = "modal"
                data-target   = "#modalTicketEnvivo"
                data-caller   = "{{ $call->CallerID }}"
                data-fecha    = "{{ $call->Fechatms }}"
                data-ext      = "{{ $call->IdExt }}"
                data-duracion = "{{ $call->Duracion }}"
                data-ID    = "{{ $call->ID }}">
      <img src= "{{ asset('img/btnEditar.png') }}" />
    </a>
  </td>
</tr>
