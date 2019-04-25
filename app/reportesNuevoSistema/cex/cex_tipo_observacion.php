<?php

namespace App\reportesNuevoSistema\cex;

use Illuminate\Database\Eloquent\Model;

class cex_tipo_observacion extends Model
{
    protected $connection = 'cobefec3Reportes';
    protected $table = 'cex_tipo_observacion';
    protected $primaryKey='id';
    public $timestamps=true;
}
