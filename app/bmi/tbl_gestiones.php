<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_gestiones extends Model
{
    protected $connection = 'bmi';
    protected $table = 'gestiones';
    protected $primaryKey='id_gestion';
    public $timestamps=true;

    // Relación Asesor
    public function asesor() {
        return $this->hasOne('App\bmi\tbl_asesores','cedula_asesor','cedula_asesor'); // Le indicamos que se va relacionar con el atributo id_tipo
    }
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
        return $this->hasOne('App\bmi\tbl_citas','id_cita','id_cita'); // Le indicamos que se va relacionar con el atributo id_cita
    }
    // Relación Citas
    public function citaPropia() {
        return $this->hasOne('App\bmi\tbl_citas','id_cita','id_cita'); // Le indicamos que se va relacionar con el atributo id_cita
    }
    // Relación Citas
    public function citaHistorial() {
        return $this->hasOne('App\bmi\tbl_citas_historial','id_cita_orig','id_cita'); // Le indicamos que se va relacionar con el atributo id_cita
    }
    // Relación Producto
    public function producto() {
        return $this->hasOne('App\bmi\tbl_producto','id_producto','id_producto'); // Le indicamos que se va relacionar con el atributo id_producto
    }
    // Relación Cliente
    public function gestionCobefec() {
        return $this->hasOne('App\bmi\tbl_clientes','id_gestion','id_gestion_cobefec'); // Le indicamos que se va relacionar con el atributo id_producto
    }
}