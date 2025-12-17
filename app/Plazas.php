<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plazas extends Model
{
  protected $connection = 'sqlsrvgr';
  protected $table      = 'Te_Almacenes';
}
