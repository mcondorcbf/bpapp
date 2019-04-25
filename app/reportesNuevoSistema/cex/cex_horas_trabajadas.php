<?php

namespace App\reportesNuevoSistema\cex;

use Illuminate\Database\Eloquent\Model;

class cex_horas_trabajadas extends Model
{
    protected $connection = 'cobefec3Reportes';
    protected $table = 'cex_horas_trabajadas';
    protected $primaryKey='id';
    public $timestamps=true;
}
