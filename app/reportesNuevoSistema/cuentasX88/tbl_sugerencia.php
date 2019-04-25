<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_sugerencia extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'sugerencia';
    protected $primaryKey='id';
    public $timestamps=true;
}
