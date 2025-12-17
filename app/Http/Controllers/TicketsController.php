<?php

namespace App\Http\Controllers;
use App\EnVivo;
use App\TiposFallas;
use App\Tickets;
use App\Plazas;
use App\TiposDeServicio;
use App\Compatibilidades;
use App\Periodos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TicketsController extends Controller{
  protected $permisos;
  public function __construct(){
      $this->middleware('auth');
      $this->middleware('userGR');
  }
  public function controllerPermisos($clave){

    $permiso = session()->get($clave);
      # MODO AJAX EN TRUE, DEVUELVE TRUE O FALSE
      if( isset($permiso) )
          return true;
      else
          return false;


  }
  public function index(){
    $permiso = $this->controllerPermisos('opcion_page_envivo');
    if(!$permiso)
      return redirect()->route('permiso.error')->with('error', 'No tienes permiso para acceder a esta opción');

      $llamadas        = EnVivo::whereNull('PuntoVenta')->whereNull('fechacierre')->orderBy('Fechatms', 'Desc')->paginate(20);


      $plazas          = collect(Tickets::Plazas())->SortBy('NombreAlmacen')->pluck('NombreAlmacen','IdAlmacen');
      $datos           = collect(Tickets::PuntosdeVenta('CJS'))->prepend([null => "Opcion"]);
      $puntos          = $datos->sortBy('NombreAlmacen')->pluck('NombreAlmacen','IdAlmacen');
      $codigos         = $datos->sortBy('EtiquetaAlmacen')->pluck('EtiquetaAlmacen','EtiquetaAlmacen');
      $rutas           = $datos->sortBy('Ruta')->pluck('Ruta','Ruta')->prepend([null => "Selecciona una opción"]);

      $tiposDeServicio = collect(Tickets::TiposDeServicio())->prepend(null)->pluck('Descripcion','IdTipoServicio');

      $fallas          = Collect(Tickets::TiposDeFalla("", "", "", ""))->where('EstadoLogico', 1)->prepend(null); #1. Cliente 2. Tecnico
      $fallasCliente   = $fallas->where('TipoReporte', 1)->SortBy('Descripcion')->prepend(null)->pluck('Descripcion','IdFalla');
      $fallasTecnico   = $fallas->where('TipoReporte', 2)->SortBy('Descripcion')->prepend(null)->pluck('Descripcion','IdFalla');




      return view('home', compact('llamadas','rutas','codigos','puntos','plazas','tiposDeServicio','fallasCliente','fallasTecnico'));

  }
  public function pendientes(){

      $permiso = $this->controllerPermisos('opcion_page_pendiente');
      if(!$permiso)
        return redirect()->route('permiso.error')->with('error', 'No tienes permiso para acceder a esta opción');

      $llamadas = EnVivo::withNoLock()
      ->whereNULL('fechacierre')
      ->whereNotNULL('CodigoIdentificacion')
      ->where('IdExt',"<",3000)
      ->where('Duracion',">",0)
      ->orderBy('Fechatms', 'Desc')
      ->paginate(50);
      return view('tickets.pendientes', compact('llamadas'));

  }
  public function historial(){

    ini_set('max_execution_time', '300');
    $permiso = $this->controllerPermisos('opcion_page_historial');
    if(!$permiso)
      return redirect()->route('permiso.error')->with('error', 'No tienes permiso para acceder a esta opción');
    $llamadas = EnVivo::whereNotNULL('fechacierre')->orderBy('Fechatms', 'Desc')->paginate(50);
    return view('tickets.historial', compact('llamadas'));
  }



  /*
  Función que muestra el modal de ticket en el apartado de envivo al presionar el botón Editar
  Se muestra solo si no tiene código de identificación asignado
  */
  public function view($id){
      
    $ticket = EnVivo::where('id', $id)->first();
    $periodo = Periodos::where('Id', $id)->first();

    if( !isset($ticket) ){
      $res['status'] = 'error';
      return response()->json($res);
    }
    // Crea en nulo las variables
    $motivo = null;
    $muestraTr = "N";
    $horaFinal = null;
    $fechaFinal = null;
    $horaInicial = null;
    $fechaInicial = null;
    $fallasCliente = null;
    $fallasTecnico = null;
    $fallaCliente =  null;
    $fallaTecnico =  null;
    $muestraCerradoTr= "N";
    $tipoServicioV  = ($ticket->TipoServicio);
    $plazas          = collect(Tickets::Plazas())->SortBy('NombreAlmacen')->pluck('NombreAlmacen','IdAlmacen');
    $datos           = collect(Tickets::PuntosdeVenta($ticket->IdPlaza))->prepend([null => "Opcion"]);
    $puntos          = $datos->sortBy('NombreAlmacen')->pluck('NombreAlmacen','IdAlmacen');
    $codigos         = $datos->sortBy('EtiquetaAlmacen')->pluck('EtiquetaAlmacen','EtiquetaAlmacen');
    $rutas           = $datos->sortBy('Ruta')->pluck('Ruta','Ruta')->prepend(null);
    $tiposDeServicio = collect(Tickets::TiposDeServicio())->prepend(null)->pluck('Descripcion','IdTipoServicio');    

    if( isset($ticket->TipoServicio) )
    {
       $motivo = collect(Tickets::MotivosDeLlamada($ticket->TipoServicio))->firstWhere('IdMotivoLlamada', $ticket->IdMotivoLlamada);
    }
    if(isset($motivo)){
       $fallas          = Collect(Tickets::TiposDeFalla($ticket->TipoServicio, $ticket->IdMotivoLlamada, $ticket->IdFallaxCliente, $ticket->IdFallaxTecnico ))->prepend(null); #1. Cliente 2. Tecnico
       $fallasCliente   = $fallas->where('TipoReporte', 1)->SortBy('Descripcion')->prepend(null)->pluck('Descripcion','IdFalla');
       $fallaCliente =  TiposFallas::where('IdFalla',$ticket->IdFallaxCliente)->first();
       $fallaTecnico =  TiposFallas::where('IdFalla',$ticket->IdFallaxTecnico)->first();
       $fallasTecnico   = $fallas->where('TipoReporte', 2)->SortBy('Descripcion')->prepend(null)->pluck('Descripcion','IdFalla');
    }

    if($tipoServicioV ==  5 ){
           $muestraTr = "S";
           $muestraCerradoTr= "S";
           if(isset($periodo->FechaInicial)){
                $fechaInicial = substr( $periodo->FechaInicial, 0, 4) . '-' . substr( $periodo->FechaInicial, 4, 2) . '-' . substr( $periodo->FechaInicial, 6, 2);
                $hours = substr($periodo->HoraInicial, 0, 2);
                $minutes = substr($periodo->HoraInicial, 2, 2);
                $horaInicial = $hours.':'.$minutes;
           }
           if(isset($periodo->FechaFinal)){
                $fechaFinal = substr( $periodo->FechaFinal, 0, 4) . '-' . substr( $periodo->FechaFinal, 4, 2) . '-' . substr( $periodo->FechaFinal, 6, 2);
                 $hoursFinal = substr($periodo->HoraFinal, 0, 2);
                 $minutesFinal = substr($periodo->HoraFinal, 2, 2);
                 $horaFinal = $hoursFinal.':'.$minutesFinal;
           }
    }
    return view('tickets.show', compact('ticket','rutas','codigos','puntos','plazas','tiposDeServicio', 'motivo','fallasCliente','fallasTecnico', 'fallaCliente', 'fallaTecnico', 'muestraTr', 'muestraCerradoTr', 'fechaInicial', 'horaInicial', 'fechaFinal', 'horaFinal'));
  }


  /*
  Metodo que muestra el modal de ticket en el apartado de pendientes e historial
  Solo si tiene código de identificación
  */
  public function show($id){
    $ticket = EnVivo::where('id', $id)->first();

    if( !isset($ticket) ){
      $res['status'] = 'error';
      return response()->json($res);
    }

    $motivo = null;
    $fechaInicial = null;
    $horaInicial = null;
    $fechaFinal = null;
    $horaFinal =  null;
    $muestraTr = "N";
    $muestraCerradoTr= "N";
    $fallasCliente = null;
    $fallasTecnico = null;
    # DATA TICKET
    $res['status'] = 'success';

    #PLAZAS
    $plazas          = collect(Tickets::Plazas())->SortBy('NombreAlmacen')->pluck('NombreAlmacen','IdAlmacen');
    $datos           = collect(Tickets::PuntosdeVenta($ticket->IdPlaza))->prepend([null => "Opcion"]);
    $puntos          = $datos->sortBy('NombreAlmacen')->pluck('NombreAlmacen','IdAlmacen');
    $codigos         = $datos->sortBy('EtiquetaAlmacen')->pluck('EtiquetaAlmacen','EtiquetaAlmacen');
    $rutas           = $datos->sortBy('Ruta')->prepend([null => "Opcion"])->pluck('Ruta','Ruta');

    $tiposDeServicio = collect(Tickets::TiposDeServicio())->prepend(null)->pluck('Descripcion','IdTipoServicio');

    if( isset($ticket->TipoServicio) )
    {
          $motivo = collect(Tickets::MotivosDeLlamada($ticket->TipoServicio))->where('IdMotivoLlamada', $ticket->IdMotivoLlamada)->first();
          if( isset($motivo)){
               $fallas          = Collect(Tickets::TiposDeFalla($ticket->TipoServicio, $motivo, $ticket->IdFallaxCliente, $ticket->IdFallaxTecnico))->prepend(null); #1. Cliente 2. Tecnico
               $fallasCliente   = $fallas->where('TipoReporte', 1)->SortBy('Descripcion')->prepend(null)->pluck('Descripcion','IdFalla');
                $fallasTecnico   = $fallas->where('TipoReporte', 2)->SortBy('Descripcion')->prepend(null)->pluck('Descripcion','IdFalla');
          }
          if( $ticket->TipoServicio == 5){
              $periodo =  Periodos::where('id', $id)->first();
              $muestraTr = "S";
              $muestraCerradoTr= "S";
              if(isset($periodo)  ){
                  if(isset($periodo->FechaInicial) && isset($periodo->HoraInicial)){
                      $fechaInicial =  substr( $periodo->FechaInicial, 0, 4) . '-' . substr($periodo->FechaInicial, 4,2) .'-'. substr($periodo->FechaInicial, 6, 2);
                      $horasInicial = substr($periodo->HoraInicial, 0, 2);
                      $minutosInicial = substr($periodo->HoraInicial,2,2);
                      $horaInicial =  $horasInicial.':'.$minutosInicial;
                  }

                  if(isset($periodo->FechaFinal) && isset($periodo->HoraFinal)){
                        $fechaFinal =  substr( $periodo->FechaFinal, 0, 4) . '-' . substr($periodo->FechaFinal, 4,2) .'-'. substr($periodo->FechaFinal, 6, 2);
                        $horasFinal = substr($periodo->HoraFinal, 0, 2);
                        $minutosFinal = substr($periodo->HoraFinal,2,2);
                         $horaFinal =  $horasFinal.':'.$minutosFinal;
                  }
              }
          }else if($ticket->TipoServicio ==  3 && isset($ticket->IdStattick)){ // Si es TWH y tiene un valor de estado
              if($ticket->IdStattick ==  3){ // debe de tener fecha de cierre
                $muestraCerradoTr= "S";
                $fechaFinal = $ticket->fechacierre;
                $horaFinal = $ticket->HoraCierre;
              }
          }// fin else if 
    }
    $res['body']     = view('tickets.fragmentos.form', compact('ticket','rutas','codigos','puntos','plazas','tiposDeServicio', 'motivo','fallasCliente','fallasTecnico', 'muestraTr', 'muestraCerradoTr',  'fechaInicial', 'horaInicial', 'fechaFinal', 'horaFinal'))->render();
    $res['ticket']   = $ticket;
    return response()->json($res);
  }





  /* Metodo encargado de Crear el ticket en la base de datos */
  public function store(Request $request){
    $permiso = $this->controllerPermisos('opcion_ticket_store');
    if(!$permiso){
      $res['status'] = 'error';
      return response()->json($res);
    }
    $input = $request->all();
    if( !isset($input['CallerID']) ){
        $res['status'] = 'error';
        return response()->json($res);
    }
    $input['IdStattick'] = 2;
    $input['Fechatms']   = date("Y-m-d H:i:s", time()) . ".000";
    $res['status']       = 'success';
    if( isset($input['fechacierre']) && $input['fechacierre'] == '0'){
        $input['fechacierre']   = null;
    }else{
        $input['IdStattick']   = 3;
        $input['HoraCierre']  = date("H:i:s", time());
    }

    $call = EnVivo::create($input);

    //Una vez que creo el ticket, va y asigna hora de cierre manual solo si es Purefill
     if($input['TipoServicio']==5 && isset($input['FechaInicial']) && isset($input['Hora_Inicial_Hora']) && isset($input['Hora_Inicial_Minuto'])){
          $this->agregaPeriodo( $call['ID'],$input);
     }
    return response()->json($res);
  }




  /*Metodo encargado de editar el registro desde cualquier modal */
  public function update(Request $request, $ticket){
    $permiso = $this->controllerPermisos('opcion_ticket_update');
    if(!$permiso){
      $res['status'] = 'error';
      return response()->json($res);
    }
    $input = $request->all();
     if(isset($input['FechaInicial']) && isset($input['Hora_Inicial_Hora']) && isset($input['Hora_Inicial_Minuto']) ){
            $this->agregaPeriodo($ticket,$input);
     }

    $ticket = EnVivo::where('id', $ticket)->first();

    if( !isset($ticket) ){
      $res['status'] = 'error';
      return response()->json($res);
    }
    if(isset($input['IdMotivoLlamada'])){
        $ticket->Plaza                = $input['Plaza'];
        $ticket->IdPlaza              = $input['IdPlaza'];
        $ticket->Ruta                 = $input['Ruta'];
        $ticket->userid               = $input['userid'];
        $ticket->IdCompania           = $input['IdCompania'];
        $ticket->CodigoIdentificacion = $input['CodigoIdentificacion'];
        $ticket->PuntoVenta           = $input['PuntoVenta'];
        $ticket->IdMotivoLlamada      = $input['IdMotivoLlamada'];
        $ticket->TipoServicio         = $input['TipoServicio'];
        $ticket->Observaciones        = $input['Observaciones'];
        $ticket->IdFallaxCliente      = $input['IdFallaxCliente'];
        $ticket->IdFallaxTecnico      = $input['IdFallaxTecnico'];

    }

    if( isset($input['fechacierre']) && $input['fechacierre'] == '0'){
      $ticket->IdStattick          = 2;
      $ticket->fechacierre         = null;
      $ticket->HoraCierre         = null;
      $res['close']                = "nok";
    }else{
      $ticket->IdStattick          = 3;
      if(isset($input['FechaFinal']) && isset($input['Hora_Final_Hora']) && isset($input['Hora_Final_Minutos']) && $input['TipoServicio'] ==3){
          $ticket->fechacierre         = $input['FechaFinal'];
          $ticket->HoraCierre          = date($input['Hora_Final_Hora'].':'.$input['Hora_Final_Minutos'].':00', time());
      }else{
          $ticket->fechacierre         = $input['fechacierre'];
          $ticket->HoraCierre          = date("H:i:s", time());
      }
      $res['close']                = "ok";
    }

    // Aqui lo actualiza
    $doit = $ticket->save();
    $res['response'] = $doit;
    $res['ticket']   = $ticket;

    if($doit)
      $res['status']   = 'success';
    else
      $res['status']   = 'error';

    return response()->json($res);


  }

 public function agregaPeriodo($idTicke, $input){

        $input['FechaInicial'] = str_replace("-", "",  $input['FechaInicial']);
        $input['HoraInicial']  =  $input['Hora_Inicial_Hora'].  $input['Hora_Inicial_Minuto']. "00";


       if(isset($input['FechaFinal']) &&  isset($input['Hora_Final_Hora']) && isset($input['Hora_Final_Minutos'])){
           $input['FechaFinal'] = str_replace("-", "",  $input['FechaFinal']);
           $input['HoraFinal']  =  $input['Hora_Final_Hora'].  $input['Hora_Final_Minutos']. "00";
       }

        $periodo =  Periodos::where('id', $idTicke)->first();
        if(isset($periodo)){

            $periodo->Id = $idTicke;
            $periodo->FechaInicial = (str_replace("-","", $input['FechaInicial']));
            $periodo->HoraInicial = $input['Hora_Inicial_Hora'].  $input['Hora_Inicial_Minuto']. "00";

            if(isset($input['FechaFinal']) && isset($input['Hora_Final_Hora']) && isset($input['Hora_Final_Minutos'])){
                $periodo->FechaFinal = (str_replace("-", "", $input['FechaFinal']));
                $periodo->HoraFinal = $input['Hora_Final_Hora'].  $input['Hora_Final_Minutos']. "00";
            }
            $periodo->UsuarioCambio = $input['userid'];
            $periodo->FechaCambio = date("Ymd");
            $periodo->HoraCambio = date("His");
            $doit =  $periodo->save();
    } else{
        //si trae info pero no existe, se agrega
        $input['UsuarioAlta'] = $input['userid'];
        $input['FechaAlta'] = date("Ymd");
        $input['HoraAlta'] = date("His");
        $input['EstadoLogico'] = 1;
        $input['Version'] = 0;
        $input['Id'] = $idTicke;
        $periodoPurefill =  Periodos::create($input);
    }//fin else

 }




  public function search(Request $request){
      #PCECILIA
    $permiso = $this->controllerPermisos('opcion_page_historial');
    if(!$permiso)
      return redirect()->route('permiso.error')->with('error', 'No tienes permiso para acceder a esta opción');

    $input    = $request->all();
    $llamadas = null;

    //Valida si es pendientes o historial
    if($input['origen'] == "historial"){
        $llamadas = $this->searchHistorial($input);
        return view('tickets.historial', compact('llamadas'));

    }else{
        $llamadas = $this->searchPendientes($input);
        return view('tickets.pendientes', compact('llamadas'));
    } 
    
  }


  public function searchHistorial($input){
      //PCECILIA
      $llamadas= null;
      $idAlmacenBase = null;
    if(isset($input['grupoRadio'])){
        switch($input['grupoRadio']){
            case 'idticket':
                $llamadas = EnVivo::where('ID', ((int)$input['target']) )  ->orderBy('Fechatms', 'Desc')->paginate(20)->withQueryString();
                break;
            case 'caller':
                $llamadas = EnVivo::whereNotNULL('fechacierre') ->where('CallerID', $input['target'] ) ->orderBy('Fechatms', 'Desc') ->paginate(20)->withQueryString();
                break;
            case 'plaza':
                $plazas =  Plazas::where('NombreAlmacen', 'like', '%'.$input['target'].'%')->where('TipoAlmacen', 1)->where('EstadoLogico', 1)->whereNotIn('IdCompania', [52,51])->whereNotIn('idalmacen', ['TRCSLW','MTYSLW','LSLSLW'])->first();
                if( isset($plazas->IdAlmacen)){
                    $llamadas = EnVivo::whereNotNull('PuntoVenta')->whereNotNULL('fechacierre')->where('IdPlaza', $plazas->IdAlmacen ) ->orderBy('Fechatms', 'Desc')->paginate(20)->withQueryString();
                }

                 break;
             case 'servicio':
                $tipoServicio =  TiposDeServicio::where('EstadoLogico',1) ->where('Descripcion',  'like', '%'.$input['target'].'%')->first();
                if(isset($tipoServicio->IdTipoServicio)){
                    $llamadas = EnVivo::whereNotNull('PuntoVenta')  ->whereNotNULL('fechacierre') ->where('TipoServicio', ((int)$tipoServicio->IdTipoServicio)  ) ->orderBy('Fechatms', 'Desc')->paginate(20)->withQueryString();
                }
                 break;
            case 'observaciones':
                $llamadas = EnVivo::whereNotNull('PuntoVenta')  ->whereNotNULL('fechacierre')  ->where('Observaciones', 'like' , '%'.$input['target'].'%' )  ->orderBy('Fechatms', 'Desc')  ->paginate(20)->withQueryString();
                break;
        }
    }
    return $llamadas;
  }

  public function searchPendientes($input){
      //PCECILIA
      $llamadas= null;
      $idAlmacenBase = null;
    if(isset($input['grupoRadio'])){
        switch($input['grupoRadio']){
            case 'idticket':
                $llamadas = EnVivo::where('ID', ((int)$input['target']) )  ->whereNULL('fechacierre') ->whereNotNULL('CodigoIdentificacion') ->where('IdExt',"<",3000) ->where('Duracion',">",0)  ->orderBy('Fechatms', 'Desc')->paginate(20)->withQueryString();
                break;
            case 'caller':
                $llamadas = EnVivo::where('CallerID', $input['target'] ) ->whereNULL('fechacierre') ->whereNotNULL('CodigoIdentificacion')  ->where('IdExt',"<",3000) ->where('Duracion',">",0) ->orderBy('Fechatms', 'Desc')->paginate(20)->withQueryString();
                break;
            case 'plaza':
                $plazas =  Plazas::where('NombreAlmacen', 'like', '%'.$input['target'].'%')->where('TipoAlmacen', 1)->where('EstadoLogico', 1)->whereNotIn('IdCompania', [52,51])->whereNotIn('idalmacen', ['TRCSLW','MTYSLW','LSLSLW'])->first();
                if(isset($plazas->IdAlmacen)){
                    $llamadas = EnVivo::where('IdPlaza', '=',$plazas->IdAlmacen ) ->whereNotNULL('CodigoIdentificacion') ->where('IdExt',"<",3000) ->where('Duracion',">",0) ->orderBy('Fechatms', 'Desc')->paginate(20)->withQueryString();
                }
                 break;

             case 'servicio':
                $tipoServicio =  TiposDeServicio::where('EstadoLogico',1) ->where('Descripcion',  'like', '%'.$input['target'].'%')->first();
                if(isset($tipoServicio->IdTipoServicio)){
                    $llamadas = EnVivo::where('TipoServicio', ((int)$tipoServicio->IdTipoServicio) )  ->whereNULL('fechacierre') ->whereNotNULL('CodigoIdentificacion') ->where('IdExt',"<",3000) ->where('Duracion',">",0)  ->orderBy('Fechatms', 'Desc')->paginate(20)->withQueryString();
                }

                break;
            case 'observaciones':
                $llamadas = EnVivo::where('Observaciones', 'like' , '%'.$input['target'].'%' )  ->whereNULL('fechacierre') ->whereNotNULL('CodigoIdentificacion') ->where('IdExt',"<",3000) ->where('Duracion',">",0)  ->orderBy('Fechatms', 'Desc')->paginate(20)->withQueryString();
                break;
        }
    }
    return $llamadas;
  }

  public function ajaxPuntos($idPlaza){

    $datos      = collect(Tickets::PuntosdeVenta($idPlaza));
    $idCompania = null;
    if( isset($datos)){
      $idCompania = $datos[0]->IdCompania;
      $res['idCompania'] = $idCompania;
    }

    $codigos = $datos->prepend([null => "Opcion"])->sortBy('EtiquetaAlmacen')->pluck('EtiquetaAlmacen','EtiquetaAlmacen');
    $puntos  = $datos->prepend([null => "Opcion"])->sortBy('NombreAlmacen')->pluck('NombreAlmacen','IdAlmacen');
    $rutas   = $datos->sortBy('Ruta')->prepend([null => "Opcion"])->pluck('Ruta','Ruta');
    $html    = null;

    $res['status'] = 'success';
    foreach($codigos AS $clave => $valor){
      $html .= "<option value='" . $clave . "'>" . $valor . "</option>";
    }
    $res['codigos'] = $html;

    $html = null;
    foreach($puntos AS $clave => $valor){
      $html .= "<option value='" . $clave . "'>" . $valor . "</option>";
    }
    $res['puntos'] = $html;


    $html = null;
    foreach($rutas AS $clave => $valor){
      $html .= "<option value='" . $clave . "'>" . $valor . "</option>";
    }
    $res['rutas'] = $html;



    return response()->json($res);
  }
  public function ajaxGetPorCodigo($idPlaza, $codigo){

    $datos   = collect(Tickets::GetPorCodigo($idPlaza, $codigo))->first();
    $res['status'] = 'success';
    $res['punto']  = $datos->NombreAlmacen;
    $res['codigo'] = $codigo;
    $res['IdAlmacen'] = $datos->IdAlmacen;
    $res['ruta']   = $datos->Ruta;


    return response()->json($res);
  }
  public function ajaxGetPorPunto($idPlaza, $codigo){

    $datos   = collect(Tickets::GetPorPunto($idPlaza, $codigo))->first();
    $res['status']    = 'success';
    $res['punto']     = $datos->NombreAlmacen;
    $res['codigo']    = $datos->EtiquetaAlmacen;
    $res['IdAlmacen'] = $datos->IdAlmacen;
    $res['ruta']      = $datos->Ruta;


    return response()->json($res);
  }
  public function ajaxMotivos($servicio){

        $datos   = Tickets::MotivosDeLlamada($servicio)->pluck('Descripcion','IdMotivoLlamada');
        $res['status'] = 'success';
        $html = null;

        foreach($datos AS $clave => $valor){
          $html .= "<option value='" . $clave . "'>" . $valor . "</option>";
        }
        $res['motivos'] = $html;

        return response()->json($res);


  }


  public function ajaxEnVivo(){

    $llamadas = EnVivo::whereNull('PuntoVenta')->whereNull('fechacierre')->orderBy('Fechatms', 'Desc')->paginate(20);

    $response['time']     = "<b>Ultima vez actualizado: </b>" . Carbon::now()->format('h:i:s A [d M Y]');

    if( isset($llamadas) ){
      $response['status'] = 'success';
      $response['body']   = view('tickets.tabla', compact('llamadas'))->render();
    }else{
      $response['status'] = 'no-items';
    }

    return response()->json($response);

  }
  public function ajaxFallasXServicioCliente($idServicio, $idMotivo){

    $datos   =  Collect(Tickets::FallasServicioCliente($idServicio, $idMotivo))->prepend(null)->pluck('Descripcion','IdFalla'); ;
    $res['status'] = 'success';
    $html = null;

    foreach($datos AS $clave => $valor){
      $html .= "<option value='" . $clave . "'>" . $valor . "</option>";
    }
    $res['fallas'] = $html;
    return response()->json($res);
  }

    public function ajaxFallasXServicio($idServicio, $idMotivo, $idTipoReporte){

    $datos   =  Collect(Tickets::FallasServicio($idServicio,$idMotivo, $idTipoReporte))->prepend(null)->pluck('Descripcion','IdFalla'); ;
    $res['status'] = 'success';
    $html = null;

    foreach($datos AS $clave => $valor){
      $html .= "<option value='" . $clave . "'>" . $valor . "</option>";
    }
    $res['fallas'] = $html;
    return response()->json($res);
  }
}
