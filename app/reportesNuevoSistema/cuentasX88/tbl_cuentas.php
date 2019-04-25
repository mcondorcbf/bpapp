<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_cuentas extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'cuentas';
    protected $primaryKey='id';
    public $timestamps=true;

    public function carga() {
        return $this->hasOne('App\reportesNuevoSistema\cuentasX88\tbl_id_carga','id','id_carga');
    }
}
