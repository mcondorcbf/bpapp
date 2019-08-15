<?php

namespace App\nomina;

use Illuminate\Database\Eloquent\Model;

class detalle extends Model
{
    protected $connection = 'nomina';
    protected $table = 'detalle';
    protected $primaryKey='id_detalle';
    public $timestamps=true;
}
