<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
abstract class BaseModel extends Model
{
    /**
     * Scope para aplicar WITH (NOLOCK) a todas las consultas.
     */
    public function scopeWithNoLock($query)
    {
        return $query->from(DB::raw($this->getTable() . ' WITH (NOLOCK)'));
    }
}

