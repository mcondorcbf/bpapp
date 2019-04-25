<?php

namespace App\reportesNuevoSistema\encuestasCex;

use Illuminate\Database\Eloquent\Model;

class tbl_rol extends Model
{
    protected $connection = 'encuestascex';
    protected $table = 'rol';
    protected $primaryKey='id_rol';
    public $timestamps=true;
}
