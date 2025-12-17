<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DIDs extends Model
{
    protected $connection = 'sqlsrv';
    protected $table      = 'Tb_Tickets';
    public $timestamps    = false;
    protected $primaryKey = 'IdDID';
  protected $fillable = ['Nombre','DID','Plaza','EstadoLogico','UsuarioAlta','FechaAlta'];
}
