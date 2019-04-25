<?php

namespace App\reportesNuevoSistema\cex;

use Illuminate\Database\Eloquent\Model;

class cex_tiempos_muertos extends Model
{
    protected $connection = 'cobefec3Reportes';
    protected $table = 'cex_tiempos_muertos';
    protected $primaryKey='id';
    public $timestamps=true;
}
