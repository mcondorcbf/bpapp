<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_observaciones extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'observaciones';
    protected $primaryKey='id';
    public $timestamps=true;

    public function cuenta() {
        return $this->hasOne('App\reportesNuevoSistema\cuentasX88\tbl_observaciones','id','id_cuenta');
    }
}
