<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_gestiones_propias_manuales extends Model
{
    protected $connection = 'bmi';
    protected $table = 'gestiones_propias_manuales';
    protected $primaryKey='id_gestion';
    public $timestamps=true;

    // Relación Tipo
    public function tipo() {
        return $this->hasOne('App\bmi\tbl_tipo','id_tipo','id_tipo'); // Le indicamos que se va relacionar con el atributo id_tipo
    }
    // Relación Acciones
    public function accion() {
        return $this->hasOne('App\bmi\tbl_accion','id_accion','id_accion'); // Le indicamos que se va relacionar con el atributo id_accion
    }
    // Relación Citas
    public function cita() {
        return $this->hasOne('App\bmi\tbl_citas','id_cita_propia','id_cita'); // Le indicamos que se va relacionar con el atributo id_cita_propia
    }
    // Relación Citas
    public function citaPropia() {
        return $this->hasOne('App\bmi\tbl_citas','id_cita_propia','id_cita'); // Le indicamos que se va relacionar con el atributo id_cita_propia
    }
    // Relación Citas
    public function citaHistorial() {
        return $this->hasOne('App\bmi\tbl_citas_propias_historial','id_cita_orig','id_cita_propia'); // Le indicamos que se va relacionar con el atributo id_cita_propia
    }
    // Relación Producto
    public function producto() {
        return $this->hasOne('App\bmi\tbl_producto','id_producto','id_producto'); // Le indicamos que se va relacionar con el atributo id_producto
    }
}
