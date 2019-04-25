<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_id_carga extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'id_carga';
    protected $primaryKey='id';
    public $timestamps=true;
}
