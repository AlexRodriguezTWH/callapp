<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UsuarioGR extends Model
{
    function scopeDataUserGR($query, $usuario){
      $datos = null;
      $tosql = "SELECT a.IdUsuario,b.SalContrasena, b.Contrasena,(a.NombreUsuario +' '+a.ApellidoPaternoUsuario) Nombre
            FROM tg_usuarios a
            join Tg_Contrasenas b on a.IdUsuario = b.IdUsuario
            WHERE a.IdUsuario='". trim($usuario) ."' and a.EstadoLogico=1 and b.EstadoLogico=1";

      $datos = DB::connection('sqlsrvgr')->select($tosql);
      return $datos;

    }

    function scopeRol($query, $usuario, $cia = null){
      $datos = null;
      if($cia == null){
        $tosql = "SELECT * FROM Te_Usuario_Roles WHERE IdUsuario = '". trim($usuario) ."'
        AND EstadoLogico = 1 AND (IdRol = 13 OR IdRol = 14 OR IdRol = 16)";
      }else{
        $tosql = "SELECT * FROM Te_Usuario_Roles WHERE IdUsuario = '". trim($usuario) ."' AND IdCompania = ".$cia."
        AND EstadoLogico = 1 AND (IdRol = 13 OR IdRol = 14 OR IdRol = 16)";
      }
      #echo $tosql;
      $datos = DB::connection('sqlsrvgr')->select($tosql);
      return $datos;
    }

    function scopePermisos($query, $idRol, $idCompania){
      $datos = null;
      $tosql = "SELECT * FROM Te_Escenario_Programa_Roles WHERE IdRol=". $idRol ." AND IdCompania=" . $idCompania;
      #echo $tosql;
      $datos = DB::connection('sqlsrvgr')->select($tosql);
      return $datos;
    }

    function scopePlazasAsignadas($query, $usuario){
      $datos = null;
      // PLAZAS ASIGNADAS --- HOLA
      $tosql = "SELECT a.* FROM Te_Usuario_Almacenes a
                Inner Join Te_Almacenes b On a.idcompania = b.idcompania and a.idalmacen = b.idalmacen
                Where a.IdUsuario = '" . trim($usuario) . "' and b.TipoAlmacen = 1";
      $datos = DB::connection('sqlsrvgr')->select($tosql);
      return $datos;


    }


}
