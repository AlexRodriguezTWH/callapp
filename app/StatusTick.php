<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusTick extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Tb_StatusTick';
    protected $primaryKey = 'IdStat';
    public $timestamps = false;
}
