<?php

namespace App\reportesNuevoSistema\cex;

use Illuminate\Database\Eloquent\Model;

class cex_observacion_horast extends Model
{
    protected $connection = 'cobefec3Reportes';
    protected $table = 'cex_observacion_horast';
    protected $primaryKey='id';
    public $timestamps=true;

    public function asesorCex() {
        return $this->hasOne('App\reportesNuevoSistema\cex\cex_horas_trabajadas','id','horas_trabajadas_id'); //3er parametro id clave foranea, 4to parametro clave primeria
    }

    public function tipoCex() {
        return $this->hasOne('App\reportesNuevoSistema\cex\cex_tipo_observacion','id','tipo_id'); //3er parametro id clave foranea, 4to parametro clave primeria
    }
}
