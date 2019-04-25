<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_citas_historial_manuales extends Model
{
    protected $connection = 'bmi';
    protected $table = 'citas_historial_manuales';
    protected $primaryKey='id_cita';
    public $timestamps=true;

    // Relación Clientes
    public function clientes() {
        return $this->hasOne('App\bmi\tbl_clientes','cedula_cliente','cedula_cliente'); // Le indicamos que se va relacionar con el atributo cedula_cliente
    }
    // Relación Asesor
    public function asesores() {
        return $this->hasOne('App\bmi\tbl_asesores','cedula_asesor','asesor'); // Le indicamos que se va relacionar con el atributo cedula_cliente
    }

    // Relación Acciones
    public function accion() {
        return $this->hasOne('App\bmi\tbl_accion','id_accion','id_accion'); // Le indicamos que se va relacionar con el atributo id_accion
    }

    // Relación Tipo
    public function tipo() {
        return $this->hasOne('App\bmi\tbl_tipo','id_tipo','id_tipo'); // Le indicamos que se va relacionar con el atributo id_accion
    }

    // Relación Producto
    public function producto() {
        return $this->hasOne('App\bmi\tbl_producto','id_producto','id_producto'); // Le indicamos que se va relacionar con el atributo id_accion
    }
}
