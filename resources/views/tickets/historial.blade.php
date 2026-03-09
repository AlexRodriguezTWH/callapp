@extends('layouts.backend.app')
@section('section-title')
  <h3>Historial</h3>
@endsection
@section('content')
      <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-12">
              <div class="card">
              <!-- PCECILAI -->
                <div class="card-header p-2 ">
                  @include('tickets.fragmentos.form-busqueda', ['origen' => 'historial'])
                </div>


                <div class="card-body" id="tbl-live">
                  @if( isset($llamadas) && $llamadas->count()>0  && $origen ='historial' )
                    <table class="table table-sm table-striped text-sm">
                      <thead class="bg-wh text-white">
                        <tr>
                          <th>ID</th>
                          <th>Fecha</th>
                          <th>Plaza</th>
                          <th>Ruta</th>
                          <th>Pto Venta.</th>
                          <th>Código Id.</th>
                          <th>T. Servicio</th>
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
                              <td>{{ $plaza->NombreAlmacen ?? "SP" }}</td>
                              <td>{{ $call->Ruta }}</td>
                              <td>{{ (isset($call->PV))? $call->PV->NombreAlmacen : "SN" }}</td>
                              <td>{{ $call->CodigoIdentificacion }}</td>
                              <td>{{ ( isset($call->Servicio) )? $call->Servicio->Descripcion : "-" }}</td>
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
                    <h1>No hay llamadas encontradas</h1>
                    <a href="{{ route('tickets.historial') }}" class="btn btn-lg btn-success">Ver historial</a>
                  @endif
                </div>

                <div class="card-footer">
                  @if( isset($llamadas) && $llamadas->count()>0 )
                      {{ $llamadas->links() }}
                  @endif
                </div>


              </div>
          </div>
      </div>
      @include('modals.ticket')
@endsection

@section('js_after')
  <script src="{{ asset('backend/js/ticket.js') }}"></script>
@endsection
