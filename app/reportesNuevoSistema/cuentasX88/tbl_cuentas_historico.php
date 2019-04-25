<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_cuentas_historico extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'cuentas_historico';
    protected $primaryKey='id';
    public $timestamps=true;

    public function carga() {
        return $this->hasOne('App\reportesNuevoSistema\cuentasX88\tbl_id_carga','id','id_carga');
    }
}
