<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_diners_x88 extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'cuentas_match';
    protected $primaryKey='id';
    public $timestamps=true;
}