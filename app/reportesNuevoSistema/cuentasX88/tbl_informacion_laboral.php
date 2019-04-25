<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_informacion_laboral extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'informacion_laboral';
    protected $primaryKey='id';
    public $timestamps=true;
}
