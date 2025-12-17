<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnVivo extends BaseModel
{
    protected $connection = 'sqlsrv';
    protected $table      = 'Tb_Tickets';
    public $timestamps    = false; 
    protected $primaryKey = 'ID';
    protected $fillable   = ['IdCompania','Fechatms','IdExt','CallerID','Duracion','IdPlaza','Plaza','Ruta','PuntoVenta','CodigoIdentificacion','TipoServicio','IdMotivoLlamada','Observaciones','userid', 'IdFallaxCliente', 'IdFallaxTecnico'];

    function PV(){
      return $this->hasOne('App\PuntoDeVenta', 'IdAlmacen' ,'PuntoVenta');
    }

    function Plaza(){
      return $this->hasOne('App\Plazas', 'IdAlmacen' ,'Plaza');
    }

    function Servicio(){
      return $this->hasOne('App\TiposDeServicio', 'IdTipoServicio' ,'TipoServicio');
    }

    function MotivoLlamada(){
      return $this->hasOne('App\MotivosLlamada', 'IdMotivoLlamada' ,'IdMotivoLlamada');
    }

}
