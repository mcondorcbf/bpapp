<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_contactabilidad extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'contactabilidad';
    protected $primaryKey='id';
    public $timestamps=true;
}
