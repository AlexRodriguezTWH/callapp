{!! Form::open(['method' => 'POST', 'role'=>'form' ]) !!}
{!! Form::hidden('userid',  session('gr_user') ) !!}
  <div class="row">
  <!-- En vivo, el btn de editar -->
    <div class="col-sm-12 col-md-6 col-lg-6">
      <table class="table table-striped table-sm">
        <tbody>
            <tr> <th> ID       </th> <td> {{ $ticket->ID }}             </td>  </tr>
            <tr> <th> Fecha    </th> <td> {{ $ticket->Fechatms }}       </td>  </tr>
            <tr> <th> Caller   </th> <td> {{ $ticket->CallerID }}       </td>  </tr>
            <tr> <th> Ext.     </th> <td> {{ $ticket->IdExt }}          </td>  </tr>
            <tr> <th> Duración </th> <td> {{ $ticket->Duracion }} segs  </td>  </tr>
            <tr>
               <td> {{ Form::label('Plaza', 'Plaza') }}               </td>
               <td>
                   {{ Form::select('Plaza',$plazas, $ticket->IdPlaza, ['class' => 'form-control form-control-sm', 'id'=>'modalIdPlaza']) }}
                   {!! Form::hidden('IdPlaza', $ticket->IdPlaza, ['id'=>'IdPlaza']) !!}
                   {!! Form::hidden('IdCompania', $ticket->IdCompania, ['id'=>'IdCompania']) !!}
               </td>
            </tr>

            <!-- PCECILIA
            <tr>
              <td> {{ Form::label('CodigoIdentificacion', 'Código editar de identificación', ['class' => '']) }} </td>
              <td> {{ Form::select('CodigoIdentificacion', $codigos,  !isset($ticket->CodigoIdentificacion)? null: $ticket->CodigoIdentificacion , ['class' => 'form-control form-control-sm', 'id'=>'inpt_codigos']) }} </td>
            </tr> -->
            <tr>
                <td>{{ Form::label('codigoFiltro_inpt_codigos', 'Código de identificación') }}</td>

                    <td colspan="2">
                       <div class="row">
                           <div class="col-md-6">
                               <input type="text" id="codigoFiltro_inpt_codigos" class="form-control" placeholder="Busca código" value="<?php echo isset($ticket->CodigoIdentificacion) ? $ticket->CodigoIdentificacion : ''; ?>">
                               </div>
                           <div class="col-md-6">
                               {{ Form::select('CodigoIdentificacion', $codigos, !isset($ticket->CodigoIdentificacion)? null: $ticket->CodigoIdentificacion , ['class' => 'form-control', 'id' => 'inpt_codigos']) }}
                           </div>
                       </div>
                   </td>

            </tr>



            <tr>
              <td> {{ Form::label('PuntoVenta', 'Punto de venta', ['class' => '']) }}</td>
              <td> {{ Form::select('PuntoVenta',  $puntos,  $ticket->PuntoVenta ?? null, ['class' => 'form-control form-control-sm', 'id'=>'inpt_puntos']) }}  </td>
            </tr>
            <tr>
              <td> {{ Form::label('Ruta', 'Ruta', ['class' => '']) }}</td>
              <td> {{ Form::select('Ruta', $rutas,  $ticket->Ruta, ['class' => 'form-control form-control-sm', 'id'=>'inpt_rutas']) }} </td>
            </tr>
            <tr>
              <td>{{ Form::label('fechacierre', 'Estado de ticket') }}</td>
              <td>
                {{ Form::select('fechacierre', [date('Y-m-d H:i:s', time()) => 'Cerrar', '0' => 'Pendiente'] , 0, ['class' => 'form-control form-control-sm']) }}
              </td>
            </tr>
        </tbody>
      </table>
    </div>

    <!-- DETALLES -->
    <div class="col-sm-12 col-md-6 col-lg-6">
      <table class="table table-borderless">
      
        <tr>
          <td>
            {{ Form::label('TipoServicio', 'Servicio') }}
            {{ Form::select('TipoServicio',  $tiposDeServicio ?? null,  (!isset($ticket->TipoServicio))? [null => 'Selecciona un servicio'] : $ticket->TipoServicio, ['class' => 'form-control form-control-sm', 'id'=>'inpt_servicios_edit']) }}
          </td>
          <td>
            {{ Form::label('IdMotivoLlamada', 'Motivo llamada') }}
            {{ Form::select('IdMotivoLlamada',  isset($motivo)? [$motivo->IdMotivoLlamada => $motivo->Descripcion] : [''=>'Selecciona un servicio'],  $ticket->IdMotivoLlamada, ['class' => 'form-control form-control-sm', 'id'=>'inpt_motivos_edit']) }}
            </td>
        </tr>

        <tr>
          <td>
            {{ Form::label('IdFallaxCliente', 'Falla Cliente') }}
            {{ Form::select('IdFallaxCliente',  isset($fallaCliente)? [$fallaCliente->IdFalla => $fallaCliente->Descripcion] : [''=>'Selecciona una falla cliente '],  $ticket->IdFallaxCliente, ['class' => 'form-control form-control-sm', 'id'=>'IdFallaxCliente']) }}

          </td>
          <td>
            {{ Form::label('IdFallaxTecnico', 'Falla Tecnico') }}
            {{ Form::select('IdFallaxTecnico',  isset($fallaTecnico)? [$fallaTecnico->IdFalla => $fallaTecnico->Descripcion] : [''=>'Selecciona una falla tecnico '],  $ticket->IdFallaxTecnico, ['class' => 'form-control form-control-sm', 'id'=>'IdFallaxTecnico']) }}

          </td>
        </tr>

    <!-- PCECILIA -->   

<tr id="trLlegadaReal" style="{{ ($muestraTr != 'S') ? 'display:none;' : '' }}">

    <td>
        {{ Form::label('FechaInicial', 'Fecha recepción real') }}
        {{ Form::date('FechaInicial', isset($fechaInicial) ? $fechaInicial : old('FechaInicial') , ['class' => 'form-control form-control-sm']) }}
    </td>
 
    <td>

        {{ Form::label('Hora_Inicial_Hora', 'Hora recepción real') }}

@php
    $hora = null;
    $minuto = null;

    if (isset($horaInicial) && $horaInicial) {
        $partes = explode(':', $horaInicial);
        $hora = $partes[0] ?? null;
        $minuto = $partes[1] ?? null;
    }

    $hora = old('Hora_Inicial_Hora', $hora);
    $minuto = old('Hora_Inicial_Minuto', $minuto);

    $horas = ['--' => '--'] + array_combine(
        array_map(function ($h) {
            return str_pad($h, 2, '0', STR_PAD_LEFT);
        }, range(0, 23)),
        array_map(function ($h) {
            return str_pad($h, 2, '0', STR_PAD_LEFT);
        }, range(0, 23))
    );

    $minutos = ['--' => '--'] + array_combine(
        array_map(function ($m) {
            return str_pad($m, 2, '0', STR_PAD_LEFT);
        }, range(0, 59)),
        array_map(function ($m) {
            return str_pad($m, 2, '0', STR_PAD_LEFT);
        }, range(0, 59))
    );
@endphp

<div class="d-flex align-items-center gap-2">
    {{ Form::select('Hora_Inicial_Hora', $horas, $hora, ['class' => 'form-control form-control-sm', 'style' => 'width: 70px', 'id' => 'Hora_Inicial_Hora']) }}
    <span> : </span>
    {{ Form::select('Hora_Inicial_Minuto', $minutos, $minuto, ['class' => 'form-control form-control-sm', 'style' => 'width: 70px', 'id' => 'Hora_Inicial_Minuto']) }}
</div>



     </td>
</tr>






<tr  id="trCerradaReal" style="{{ ($muestraCerradoTr != 'S') ? 'display:none;' : '' }}">
    <td>
        {{ Form::label('FechaFinal', 'Fecha cierre real') }}
        {{ Form::date('FechaFinal', isset($fechaFinal) ? $fechaFinal : old('FechaFinal'), ['class' => 'form-control form-control-sm']) }}
    </td>
    <td>
          {{ Form::label('Hora_Final_Hora', 'Hora cierre real') }}
<!-- aqui comienzan los cambios de la hora y los minutos-->
@php
    $hora = null;
    $minuto = null;

    if (isset($horaFinal) && $horaFinal) {
        $partes = explode(':', $horaFinal);
        $hora = $partes[0] ?? null;
        $minuto = $partes[1] ?? null;
    }

    $hora = old('Hora_Final_Hora', $hora);
    $minuto = old('Hora_Final_Minutos', $minuto);

    $horas = ['--' => '--'];
    foreach (range(0, 23) as $h) {
        $key = str_pad($h, 2, '0', STR_PAD_LEFT);
        $horas[$key] = $key;
    }

    $minutos = ['--' => '--'];
    foreach (range(0, 59) as $m) {
        $key = str_pad($m, 2, '0', STR_PAD_LEFT);
        $minutos[$key] = $key;
    }
@endphp

<div class="d-flex align-items-center gap-2">
    {{ Form::select('Hora_Final_Hora', $horas, $hora, [
        'class' => 'form-control form-control-sm',
        'style' => 'width: 70px',
        'id' => 'Hora_Final_Hora'
    ]) }}

    <span> : </span>

    {{ Form::select('Hora_Final_Minutos', $minutos, $minuto, [
        'class' => 'form-control form-control-sm',
        'style' => 'width: 70px',
        'id' => 'Hora_Final_Minutos'
    ]) }}
</div>
        
        
        
    </td>
</tr>
        <tr>
          <td colspan="2">
            {{ Form::label('Observaciones', 'Observaciones') }}
            {{ Form::textarea('Observaciones', $ticket->Observaciones, ['class' => 'form-control','rows'=> 3]) }}

            <!-- <button class="btn btn-lg btn-success mt-4 float-right" type="button" data-id="{{ $ticket->ID }}" id="btnUpdateTicket">Actualizar</button> -->
            <input type="image"
                  src="{{ asset('img/btnGuardar.png') }}"
                  border="0"
                  data-id="{{ $ticket->ID }}"
                  id="btnUpdateTicket"
                  class=" mt-4 float-right" />

          </td>
        </tr>
      </table>
    </div>

  </div>



{!! Form::close() !!}

<script>

 document.addEventListener('DOMContentLoaded', function () {
    const fechaInicial = document.getElementById('FechaInicial');
    const fechaFinal = document.getElementById('FechaFinal');


    const horaInicialHora = document.getElementById('Hora_Inicial_Hora');  
    const horaInicialMinuto = document.getElementById('Hora_Inicial_Minuto');

    const horaFinalHora = document.getElementById('Hora_Final_Hora');
    const horaFinalMinuto = document.getElementById('Hora_Final_Minutos');

     if(fechaFinal && horaFinalHora && horaFinalMinuto){


          fechaFinal.addEventListener('change', function () {
              const tipoServicio = document.getElementById('inpt_servicios_edit').value;
              if(fechaInicial.value.trim() == ""  && tipoServicio == 5){
                  limpiaCierre();
                  toastr.error('Favor de agregar fecha de recepción.');
              } else if (tipoServicio == 3 || tipoServicio == 5){
                  let obtieneCreacion = "{{ $ticket->Fechatms }}";
                  const fechaCreacion = new Date(obtieneCreacion);
                  const fechaActual = fechaCreacion.toISOString().split('T')[0];

                  if(!validaFechas((tipoServicio == 5 ? fechaInicial.value : fechaActual ), fechaFinal.value)){
                      limpiaCierre();
                      toastr.error('La fecha de cierre debe ser menor que la fecha de creación');
                  }
              }
          });

          horaFinalHora.addEventListener('change', function () {
              //Valida que tenga fecha de cierre
              if(fechaFinal.value.trim() != ""){
                  const tipoServicio = document.getElementById('inpt_servicios_edit').value;
                  const fechaFinalCompleta = `${fechaFinal.value}T${String(horaFinalHora.value).padStart(2, '0')}:${String("00").padStart(2, '0')}:00`;
                  const fechaHoraCierre = new Date(fechaFinalCompleta);
                  if(tipoServicio == 5){
                      const fechaInicialCompleta = `${fechaInicial.value}T${String(horaInicialHora.value).padStart(2, '0')}:${String("00").padStart(2, '0')}:00`;
                      var fechaHoraInicial = new Date(fechaInicialCompleta);
                      if(fechaHoraCierre <  fechaHoraInicial){
                           $("#Hora_Final_Hora").val("");
                           $("#Hora_Final_Minutos").val("");
                           toastr.error('La fecha y hora de recepción debe ser menor que la fecha de cierre');
                      }
                  }else if(tipoServicio ==3){

                      let obtieneCreacion = "{{ $ticket->Fechatms }}";
                      const fechaHoraInicial = new Date(obtieneCreacion);
                      fechaHoraInicial.setMinutes(0);
                      fechaHoraInicial.setSeconds(0);
                      fechaHoraInicial.setMilliseconds(0);
                      if(fechaHoraInicial > fechaHoraCierre){
                          //Aqui nomas limpia le hora
                           $("#Hora_Final_Hora").val("");
                           $("#Hora_Final_Minutos").val("");
                          toastr.error('La fecha y hora de recepción debe ser menor que la fecha de cierre');
                      }
                  }
              }else{
                  toastr.error('Favor de seleccionar la fecha de cierre');
              }
          }); //finaliza hora

          horaFinalMinuto.addEventListener('change', function () {
                            //Valida que tenga fecha de cierre
              if(fechaFinal.value.trim() != "" && horaFinalHora.value.trim() !=""){
                  const tipoServicio = document.getElementById('inpt_servicios_edit').value;
                  const fechaFinalCompleta = `${fechaFinal.value}T${String(horaFinalHora.value).padStart(2, '0')}:${String(horaFinalMinuto.value).padStart(2, '0')}:00`;
                  const fechaHoraCierre = new Date(fechaFinalCompleta);
                  if(tipoServicio == 5){
                      const fechaInicialCompleta = `${fechaInicial.value}T${String(horaInicialHora.value).padStart(2, '0')}:${String(horaInicialMinuto.value).padStart(2, '0')}:00`;
                      var fechaHoraInicial = new Date(fechaInicialCompleta);
                      if(fechaHoraCierre <  fechaHoraInicial){
                          $("#Hora_Final_Minutos").val("");
                          toastr.error('La fecha y hora de recepción debe ser menor que la fecha de cierre');
                      }
                  }else if(tipoServicio ==3 ){
                     // var fechaHoraInicial = new Date();
                     
                      let obtieneCreacion = "{{ $ticket->Fechatms }}";
                      const fechaHoraInicial = new Date(obtieneCreacion);
                     if(fechaHoraInicial > fechaHoraCierre ){
                          $("#Hora_Final_Minutos").val("");
                          toastr.error('La fecha y hora de recepción debe ser menor que la fecha de cierre');
                      }
                  }
              }else{
                  toastr.error('Favor de seleccionar la fecha y hora de cierre');
              }
          });// finaliza minuto
      }

 });

  document.getElementById('codigoFiltro_inpt_codigos').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        const select = document.getElementById('inpt_codigos');
        let visibleOptions = [];
        for (let i = 0; i < select.options.length; i++) {
            const option = select.options[i];
            const texto = option.text.toLowerCase();

            if (texto.includes(filtro)) {
                option.style.display = '';
                visibleOptions.push(option);

            } else {
                option.style.display = 'none';
            }
        }
        // Si solo una opción coincide, seleccionarla automáticamente
        if (visibleOptions.length === 1) {
            visibleOptions[0].selected = true;
            $(visibleOptions[0]).closest('select').trigger('change');
        } else {
            // Quitar selección si hay más de una o ninguna
                $("#inpt_puntos").val("");
                $("#inpt_rutas").val("");
                 select.selectedIndex = -1;
        }
    });
        function validaFechas(fechaA, fechaB){
        if(fechaA > fechaB){
            return false;
        }
        else{
            return true;
        }
    }

    function limpiaCierre(){
        //Limpia los campos de fecha cierre
        $("#FechaFinal").val("");
        $("#Hora_Final_Hora").val("");
        $("#Hora_Final_Minutos").val("");
    }

</script>

