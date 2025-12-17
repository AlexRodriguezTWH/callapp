<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fallas extends Model
{
  protected $table = 'tb_cc_fallas';
  protected $fillable = ['tipo','descripcion', 'estado','tokenx'];
}
