<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_empresa extends Model
{
    protected $connection = 'bmi';
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';
    public $timestamps = true;

    // Relación Clientes
    public function clientes() {
        return $this->hasMany('App\bmi\tbl_clientes','cedula_cliente','cedula_cliente'); // Le indicamos que se va relacionar con el atributo cedula_cliente
    }
}