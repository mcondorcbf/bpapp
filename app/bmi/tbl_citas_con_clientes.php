<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_citas_con_clientes extends Model
{
    protected $connection = 'bmi';
    protected $table = 'tbl_citas_con_clientes';
    protected $primaryKey='id_parametro_citas_asesores';
    public $timestamps=true;

    // Relación parametros citas
    public function parametro_cita() {
        return $this->hasOne('App\bmi\tbl_parametros_citas','id_parametros_citas','id_parametros_citas'); // Le indicamos que se va relacionar con el atributo id_parametros_citas
    }
    // Relación ranking clientes
    public function ranking_cliente() {
        return $this->hasOne('App\bmi\tbl_ranking_cliente','id_ranking_cliente','id_ranking_cliente'); // Le indicamos que se va relacionar con el atributo id_parametros_citas
    }
}
