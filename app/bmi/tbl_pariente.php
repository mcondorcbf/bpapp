<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_pariente extends Model
{
    protected $connection = 'bmi';
    protected $table = 'pariente';
    protected $primaryKey='id_pariente';
    public $timestamps=true;

    // Relación Parentesco
    public function parentesco() {
        return $this->hasOne('App\bmi\tbl_parentesco','id_parentesco','id_parentesco'); // Le indicamos que se va relacionar con el atributo id_parentesco
    }

    // Relación Clientes
    public function clientes() {
        return $this->hasOne('App\bmi\tbl_clientes','cedula_cliente','cedula_cliente'); // Le indicamos que se va relacionar con el atributo cedula_cliente
    }

    // Relación Direcciones
    public function direccion() {
        return $this->hasMany('App\bmi\tbl_direccion','id_direccion','id_direccion'); // Le indicamos que se va relacionar con el atributo id_direccion
    }

}
