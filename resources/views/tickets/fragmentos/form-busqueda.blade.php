{!! Form::open(['method' => 'GET', 'role'=>'form', 'route' => 'tickets.search' ]) !!}

  <input type="hidden" name="origen" value="{{ $origen }}">

<table class="table table-borderless">
  <tr class="row">
    <td class="col-2">
      <input type="text" class="form-control form-control-sm" placeholder="Busqueda" autocomplete="off" name="target">
    </td>
    <td class="text-muted text-sm">
      {!! Form::radio('grupoRadio','idticket', true, ['id' => 'idticket']);        !!}
      {!! Form::label('idticket', 'Por ID ticket'); !!}
      &nbsp;&nbsp;&nbsp;

      {!! Form::radio('grupoRadio','caller', true, ['id' => 'caller']);        !!}
      {!! Form::label('caller', 'Por CallerID'); !!}
      &nbsp;&nbsp;&nbsp;

      {!! Form::radio('grupoRadio','plaza', true, ['id' => 'plaza']);        !!}
      {!! Form::label('plaza', 'Por Plaza'); !!}
      &nbsp;&nbsp;&nbsp;

      {!! Form::radio('grupoRadio','servicio', true, ['id' => 'servicio']);        !!}
      {!! Form::label('servicio', 'Por Servicio'); !!}
      &nbsp;&nbsp;&nbsp;

      {!! Form::radio('grupoRadio','observaciones', true, ['id' => 'observaciones']);        !!}
      {!! Form::label('observaciones', 'Por Observaciones'); !!}
    </td>
    <td class="col-2">
    <!-- PCECILIA -->
      <!-- <button type="submit" class="btn btn-sm btn-success"> </button> -->
      <input type="image" src="{{ asset('img/btnBuscar.png') }}" border="0" alt="Submit" />
    </td>
  </tr>
</table>

{!! Form::close() !!}
