<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_citas_historial extends Model
{
    protected $connection = 'bmi';
    protected $table = 'citas_historial';
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

    // Relación Gestion
    public function gestion() {
        return $this->hasOne('App\bmi\tbl_gestiones','id_gestion','id_gestion'); // Le indicamos que se va relacionar con el atributo id_gestion
    }

    // Relación Citas
    public function gestionCobefec() {
        return $this->hasOne('App\bmi\tbl_citas','id_gestion_cobefec','id_gestion_cobefec'); // Le indicamos que se va relacionar con el atributo id_gestion_cobefec
    }
}
