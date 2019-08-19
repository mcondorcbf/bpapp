<?php

namespace App\nomina;

use Illuminate\Database\Eloquent\Model;

class detalle extends Model
{
    protected $connection = 'nomina';
    protected $table = 'detalle';
    protected $primaryKey='id_detalle';
    public $timestamps=true;

    public function tipo() {
        return $this->hasOne('App\nomina\tipo','id_tipo','id_tipo');
    }
}
