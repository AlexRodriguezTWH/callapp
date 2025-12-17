<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivosLlamada extends Model
{
  protected $connection = 'sqlsrv';
  protected $table = 'Tb_MotivosLlamadas';
}
