<?php

namespace App\reportesNuevoSistema\encuestasCex;

use Illuminate\Database\Eloquent\Model;

class tbl_encuesta extends Model
{
    protected $connection = 'encuestascex';
    protected $table = 'encuestas';
    protected $primaryKey='id_encuesta';
    public $timestamps=true;
}
