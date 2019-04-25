<?php

namespace App\reportesNuevoSistema\encuestasCex;

use Illuminate\Database\Eloquent\Model;

class tbl_resultados extends Model
{
    protected $connection = 'encuestascex';
    protected $table = 'resultados';
    protected $primaryKey='id_resultado';
    public $timestamps=true;
}
