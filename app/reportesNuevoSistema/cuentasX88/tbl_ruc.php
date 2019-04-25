<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_ruc extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'ruc';
    protected $primaryKey='id';
    public $timestamps=true;
}
