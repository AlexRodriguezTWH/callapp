{!! Form::open(['method' => 'POST', 'role'=>'form']) !!}
{!! Form::hidden('userid',  session('gr_user') ) !!}
  <div class="row">
  <!-- Nuevo  -->

    <div class="col-sm-12 col-md-6 col-lg-6">
      <table class="table table-striped table-sm">
        <tbody>
            <tr> <th> ID       </th> <td> Automatico / {{ session('gr_user') }}  </td>  </tr>
            <tr> <th> Fecha    </th> <td> Automatico      </td>  </tr>
            <tr> <th> Caller   </th> <td> {{ Form::text('CallerID',null, ['class' => 'form-control form-control-sm']) }}  </td>  </tr>
            <tr> <th> Ext.     </th> <td> {{ Form::text('IdExt',0, ['class' => 'form-control form-control-sm']) }}     </td>  </tr>
            <tr> <th> Duración </th> <td> {{ Form::text('Duracion',1, ['class' => 'form-control form-control-sm']) }}  </td>  </tr>
            <tr>
               <td> {{ Form::label('Plaza', 'Plaza') }}               </td>
               <td>
                   {{ Form::select('Plaza', $plazas ?? null, 'CJS', ['class' => 'form-control form-control-sm', 'id'=>'modalIdPlaza_full']) }}
                   {!! Form::hidden('IdPlaza', 'CJS', ['id'=>'IdPlaza']) !!}
                   {!! Form::hidden('IdCompania', 2, ['id'=>'IdCompania']) !!}
                            
               </td>
            </tr>

            <!--  PCECILIA -->

<tr>
    <td>{{ Form::label('inpt_codigos_full', 'Código de identificación') }}</td>
    <!-- PCECILIA -->
<td colspan="2">
    <div class="row">
        <div class="col-md-6">
            <input type="text" id="codigoFiltro" class="form-control" placeholder="Busca código" value="<?php echo isset($ticket->CodigoIdentificacion) ? $ticket->CodigoIdentificacion : ''; ?>">
        </div>
        <div class="col-md-6">
            {{ Form::select('CodigoIdentificacion', $codigos, $ticket->CodigoIdentificacion ?? null, ['class' => 'form-control', 'id' => 'inpt_codigos_full']) }}
        </div>
    </div>
</td>



</tr>
   
            <tr>
              <td> {{ Form::label('PuntoVenta', 'Punto de venta', ['class' => '']) }}</td>
              <td> {{ Form::select('PuntoVenta',  $puntos ?? null,  null, ['class' => 'form-control form-control-sm', 'id'=>'inpt_puntos_full']) }}  </td>
            </tr>
            <tr>
              <td> {{ Form::label('Ruta', 'Ruta', ['class' => '']) }}</td>
              <td> {{ Form::select('Ruta', $rutas ?? null,  null, ['class' => 'form-control form-control-sm', 'id'=>'inpt_rutas_full']) }} </td>
            </tr>
            <tr>
              <td>{{ Form::label('fechacierre', 'Estado de ticket') }}</td>
              <td>
                {{ Form::select('fechacierre', [date('Y-m-d H:i:s', time()) => 'Cerrar', '0' => 'Pendiente'] ,  0, ['class' => 'form-control form-control-sm']) }}
              </td>
            </tr>
        </tbody>
      </table>
    </div>

    <!-- DETALLES -->
    <div class="col-sm-12 col-md-6 col-lg-6">
      <table class="table table-borderless">
        <tr>
          <td> {{ Form::label('TipoServicio', 'Servicio') }}
               {{ Form::select('TipoServicio',  $tiposDeServicio ?? null,  [''=>'Selecciona una opción'], ['class' => 'form-control form-control-sm', 'id'=>'inpt_servicios']) }}
          </td>
          <td> {{ Form::label('IdMotivoLlamada',  'Motivo llamada') }}
               {{ Form::select('IdMotivoLlamada', [''=>'Selecciona un motivo de llamada'],  null, ['class' => 'form-control form-control-sm', 'id'=>'inpt_motivos']) }}
          </td>
        </tr>
        <tr>
          <td>
            {{ Form::label('IdFallaxCliente', 'Falla Cliente') }}
            {{ Form::select('IdFallaxCliente',  $fallasCliente ?? null,  null, ['class' => 'form-control form-control-sm', 'id'=>'IdFallaxCliente']) }}
          </td>
          <td>
            {{ Form::label('IdFallaxTecnico', 'Falla Tecnico') }}
            {{ Form::select('IdFallaxTecnico', $fallasTecnico ?? null ,  null, ['class' => 'form-control form-control-sm', 'id'=>'IdFallaxTecnico']) }}
          </td>
        </tr>


            <!-- PCECILIA -->   

<tr id="trLlegadaReal" style="display:none;">

    <td>
        {{ Form::label('FechaInicial', 'Fecha recepción real') }}
        {{ Form::date('FechaInicial', old('FechaInicial'), ['class' => 'form-control form-control-sm' , 'id'=>'FechaInicial']) }}
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






<tr  id="trCerradaReal" style="display:none;">
    <td>
        {{ Form::label('FechaFinal', 'Fecha cierre real') }}
        {{ Form::date('FechaFinal', old('FechaFinal'), ['class' => 'form-control form-control-sm']) }}
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
            {{ Form::textarea('Observaciones', null, ['class' => 'form-control','rows'=> 3]) }}


            <input type="image"
                  src="{{ asset('img/btnGuardar.png') }}"
                  border="0"
                  id="btnStoreTicket"
                  class=" mt-4 float-right" />

            <input type="image" src="{{ asset('img/btnAnular2.png') }}" border="0" data-dismiss="modal" class="float-right mt-4 m-2 btnAnular" />

          </td>
        </tr>
      </table>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


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
              const tipoServicio = document.getElementById('inpt_servicios').value;
              if(fechaInicial.value.trim() == ""){
                  limpiaCierre();
                  toastr.error('Favor de agregar fecha de recepción.');
              } else if (tipoServicio == 5){
                  const hoy = new Date();
                  const fechaActual = hoy.toISOString().split('T')[0];
                  if(!validaFechas((tipoServicio == 5 ? fechaInicial.value : fechaActual ), fechaFinal.value)){
                      limpiaCierre();
                      toastr.error('La fecha de cierre debe ser menor que la fecha de creación');
                  }
              }
          });

          horaFinalHora.addEventListener('change', function () {
              //Valida que tenga fecha de cierre
              if(fechaFinal.value.trim() != ""){
                  const tipoServicio = document.getElementById('inpt_servicios').value;
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
                  }
              }else{
                  toastr.error('Favor de seleccionar la fecha de cierre');
              }
          }); //finaliza hora

          horaFinalMinuto.addEventListener('change', function () {
                            //Valida que tenga fecha de cierre
              if(fechaFinal.value.trim() != "" && horaFinalHora.value.trim() !=""){
                  const tipoServicio = document.getElementById('inpt_servicios').value;
                  const fechaFinalCompleta = `${fechaFinal.value}T${String(horaFinalHora.value).padStart(2, '0')}:${String(horaFinalMinuto.value).padStart(2, '0')}:00`;
                  const fechaHoraCierre = new Date(fechaFinalCompleta);
                  if(tipoServicio == 5){
                      const fechaInicialCompleta = `${fechaInicial.value}T${String(horaInicialHora.value).padStart(2, '0')}:${String(horaInicialMinuto.value).padStart(2, '0')}:00`;
                      var fechaHoraInicial = new Date(fechaInicialCompleta);
                      if(fechaHoraCierre <  fechaHoraInicial){
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

  document.getElementById('codigoFiltro').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        const select = document.getElementById('inpt_codigos_full');
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
            select.selectedIndex = -1;
            $("#inpt_puntos_full").val("");
            $("#inpt_rutas_full").val("")
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

