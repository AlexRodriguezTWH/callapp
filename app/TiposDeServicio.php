<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiposDeServicio extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Tb_TiposServicios';
    #protected $fillable = ['servicio','descripcion','estado','tokenx'];
}
