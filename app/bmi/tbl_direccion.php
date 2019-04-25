<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_direccion extends Model
{
    protected $connection = 'bmi';
    protected $table = 'direccion';
    protected $primaryKey='id_direccion';
    public $timestamps=true;

    // Relación Clientes
    public function clientes() {
        return $this->hasOne('App\bmi\tbl_clientes','cedula_cliente','cedula_cliente'); // Le indicamos que se va relacionar con el atributo cedula_cliente
    }

    // Relación Pariente
    public function pariente() {
        return $this->hasOne('App\bmi\tbl_parentesco','id_pariente','id_pariente'); // Le indicamos que se va relacionar con el atributo id_pariente
    }

    // Relación Ciudad
    public function ciudad() {
        return $this->hasOne('App\bmi\tbl_ciudad','id_ciudad','id_ciudad'); // Le indicamos que se va relacionar con el atributo id_ciudad
    }
}
