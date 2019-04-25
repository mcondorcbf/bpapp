<?php

namespace App\reportesNuevoSistema\cuentasX88;

use Illuminate\Database\Eloquent\Model;

class tbl_submotivo extends Model
{
    protected $connection = 'cuentasx88';
    protected $table = 'submotivo';
    protected $primaryKey='id';
    public $timestamps=true;

    // Relación Motivo
    public function motivo() {
        return $this->hasMany('App\reportesNuevoSistema\cuentasX88\tbl_motivo','id_motivo','id'); // Le indicamos que se va relacionar con el atributo id_motivo
    }
}
