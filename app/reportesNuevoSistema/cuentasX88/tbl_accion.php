<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_accion extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'accion';
    protected $primaryKey='id';
    public $timestamps=true;
}
