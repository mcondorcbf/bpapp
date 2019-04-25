<?php

namespace App\reportesNuevoSistema\cex;

use Illuminate\Database\Eloquent\Model;

class tbl_paradas_recorrido extends Model
{
    protected $connection = 'apiRest';
    protected $table = 'paradas_recorrido';
    protected $primaryKey='id';
    public $timestamps=true;
}
