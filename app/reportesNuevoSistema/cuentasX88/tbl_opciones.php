<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_opciones extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'opciones';
    protected $primaryKey='id';
    public $timestamps=true;
}
