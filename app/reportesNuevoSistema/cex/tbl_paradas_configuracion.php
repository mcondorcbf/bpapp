<?php

namespace App\reportesNuevoSistema\cex;

use Illuminate\Database\Eloquent\Model;

class tbl_paradas_configuracion extends Model
{
    protected $connection = 'apiRest';
    protected $table = 'paradas_configuracion';
    protected $primaryKey='id';
    public $timestamps=true;
}
