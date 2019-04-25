<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_fecha_hora_cita extends Model
{
    protected $connection = 'bmi';
    protected $table = 'tbl_fecha_hora_cita';
    protected $primaryKey='id_hora_cita';
    public $timestamps=true;

    // Relación Clientes
    public function parametros_citas() {
        return $this->hasOne('App\bmi\tbl_parametros_citas','id_parametros_citas','id_parametros_citas'); // Le indicamos que se va relacionar con el atributo cedula_cliente
    }
}