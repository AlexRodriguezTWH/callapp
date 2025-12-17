<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodos extends Model
{
  protected $connection = 'sqlsrv';
  protected $table      = 'Tb_Periodos_Purefill';
  public $timestamps    = false;
  protected $primaryKey = 'Id';
  protected $fillable = ['Id', 'FechaInicial','HoraInicial','FechaFinal','HoraFinal', 'UsuarioAlta', 'FechaAlta','EstadoLogico', 'Version' ];
}
