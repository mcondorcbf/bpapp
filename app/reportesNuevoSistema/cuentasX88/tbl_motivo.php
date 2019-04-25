<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_motivo extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'motivo';
    protected $primaryKey='id';
    public $timestamps=true;
}
