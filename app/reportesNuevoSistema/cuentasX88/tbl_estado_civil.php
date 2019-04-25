<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_estado_civil extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'estado_civil';
    protected $primaryKey='id';
    public $timestamps=true;
}
