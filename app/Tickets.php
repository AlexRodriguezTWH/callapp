<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
  protected $connection = 'sqlsrvgr';

  function scopePuntosTodos($query){

    $datos = null;
    $tosql = "select b.IdAlmacenBase Ruta, a.* from Te_Almacenes a
              inner join Te_Almacen_Rutas b on b.IdCompania = a.IdCompania and b.IdAlmacen = a.IdAlmacen and b.TipoAlmacen = 7
              where a.EstadoLogico = 1
              AND a.TipoAlmacen = 8";

    $datos = DB::connection('sqlsrvgr')->select($tosql);
    return $datos;
  }
  function scopePuntosdeVenta($query, $plaza){

    $datos = null;
    $tosql = "select b.IdAlmacenBase Ruta, a.* from Te_Almacenes a
              inner join Te_Almacen_Rutas b on b.IdCompania = a.IdCompania and b.IdAlmacen = a.IdAlmacen and b.TipoAlmacen = 7
              where a.SubAlmacen = '". $plaza ."'
              AND a.EstadoLogico = 1
              AND a.TipoAlmacen = 8
              ORDER BY EtiquetaAlmacen ASC";

    $datos = DB::connection('sqlsrvgr')->select($tosql);
    return $datos;
  }
  function scopeGetPorCodigo($query, $plaza, $codigo){

    $datos = null;
    $tosql = "select b.IdAlmacenBase Ruta, a.* from Te_Almacenes a
              inner join Te_Almacen_Rutas b on b.IdCompania = a.IdCompania and b.IdAlmacen = a.IdAlmacen and b.TipoAlmacen = 7
              where a.SubAlmacen = '". $plaza ."' AND EtiquetaAlmacen ='" . $codigo . "'
              AND a.EstadoLogico = 1
              AND a.TipoAlmacen = 8";


    $datos = DB::connection('sqlsrvgr')->select($tosql);
    return $datos;
  }
  function scopeGetPorPunto($query, $plaza, $codigo){

    $datos = null;
    $tosql = "select b.IdAlmacenBase Ruta, a.* from Te_Almacenes a
              inner join Te_Almacen_Rutas b on b.IdCompania = a.IdCompania and b.IdAlmacen = a.IdAlmacen and b.TipoAlmacen = 7
              where a.SubAlmacen = '". $plaza ."' AND a.IdAlmacen ='" . $codigo . "'
              AND a.EstadoLogico = 1
              AND a.TipoAlmacen = 8";


    $datos = DB::connection('sqlsrvgr')->select($tosql);
    return $datos;
  }

  function scopePlazas($query){

    $datos = null;
    $tosql2 ="select IdCompania, IdAlmacen, CASE
            WHEN IdAlmacen = 'GDL'  then 'GUADALAJARA PUREFIL'  else NombreAlmacen end as 'NombreAlmacen'
         from Te_Almacenes where TipoAlmacen=1
          and EstadoLogico = 1
              and IdCompania not in (52,51)
              and idalmacen not in ('TRCSLW','MTYSLW','LSLSLW')";


    $tosql = "select * from Te_Almacenes
              where TipoAlmacen = 1
              and EstadoLogico = 1
              and IdCompania not in (52,51)
              and idalmacen not in ('TRCSLW','MTYSLW','LSLSLW')";
    $datos = DB::connection('sqlsrvgr')->select($tosql2);



    return $datos;
  }
  function scopeTiposDeServicio($query){
     
    $datos = null;
    $tosql = "select * from Tb_TiposServicios WHERE EstadoLogico=1 Order by Descripcion";

    $datos = DB::connection('sqlsrv')->select($tosql);
    return $datos;
  }
  function scopeMotivosDeLlamada($query, $servicio){
    $datos = null;
    $tosql = "select * from Tb_MotivosLlamadas where IdTipoServicio = " . $servicio . " and EstadoLogico = 1 Order by Descripcion";
    $datos = DB::connection('sqlsrv')->select($tosql);
    $coleccion = collect($datos);
    return collect($datos);
  }
  function scopeTiposDeFalla($query, $TipoServicio, $idMotivo, $IdFallaCliente, $IdFallaTecnico){ # 
    $datos = null;
    $tosql =  null;

    if(isset($TipoServicio) && isset($idMotivo->IdMotivoLlamada) ){
        $tosql = "Select c.* from Tb_MotivosLlamadas a
        inner join Tb_Motivo_Falla b on a.IdMotivoLlamada =  b.IdMotivoLlamada
        inner join Tb_TiposFallas c on  c.IdFalla =  b.IdFalla
        Where a.IdTipoServicio =  ".$TipoServicio." and a.IdMotivoLlamada =  ".$idMotivo->IdMotivoLlamada." and a.EstadoLogico = 1 and c.EstadoLogico = 1
        order by c.Descripcion ";
        $datos = DB::connection('sqlsrv')->select($tosql);

        //Checamos si tiene el id del cliente

        $existeCliente = collect($datos)->contains('IdFalla', $IdFallaCliente);

        $existeTecnico = collect($datos)->contains('IdFalla', $IdFallaTecnico);

        if(!$existeCliente && isset($IdFallaCliente)){
            //Agrega el valor que tiene predefinido antes del cambio
            $sqlCliente = "Select top 1  * from Tb_TiposFallas where IdFalla = ".$IdFallaCliente." and TipoReporte = 1";
            $datosCliente = DB::connection('sqlsrv')->select($sqlCliente);
            $datos = array_merge($datos, $datosCliente);
        }

        if(!$existeTecnico &&  isset($IdFallaTecnico)){
            //Agrega el valor que tiene predefinido antes del cambio
            $sqlTecnico = "Select top 1  * from Tb_TiposFallas where IdFalla = ".$IdFallaTecnico." and TipoReporte = 2";
            $datosTecnico = DB::connection('sqlsrv')->select($sqlTecnico);
            $datos = array_merge($datos, $datosTecnico);
        }

    }
    return $datos;
  }


  function scopeDIDs($query){

    $datos = null;
    $tosql = "select * from Tb_DIDs";
    $datos = DB::connection('sqlsrv')->select($tosql);
    return $datos;
  }

 
  
    function scopeFallasServicio($query, $idServicio, $idMotivo, $idTipoReporte){

    $datos = null;
   $tosql="Select c.* from Tb_MotivosLlamadas a  inner join Tb_Motivo_Falla b on a.IdMotivoLlamada =  b.IdMotivoLlamada  inner join Tb_TiposFallas c on  c.IdFalla =  b.IdFalla Where a.IdTipoServicio =  ".$idServicio." and c.TipoReporte =  ".$idTipoReporte." and a.IdMotivoLlamada =  ".$idMotivo." and a.EstadoLogico =1 and c.EstadoLogico =1 order by c.Descripcion";
   $datos = DB::connection('sqlsrv')->select($tosql);
    return $datos;
  }

}
