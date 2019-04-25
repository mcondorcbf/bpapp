<?php

namespace App\reportesNuevoSistema\cex;

use Illuminate\Database\Eloquent\Model;

class tbl_auditoria_dispositivos extends Model
{
    protected $connection = 'apiRest';
    protected $table = 'auditoria_dispositivos';
    protected $primaryKey='id';
    public $timestamps=true;
}
