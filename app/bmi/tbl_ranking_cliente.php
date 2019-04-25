<?php

namespace App\Bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_ranking_cliente extends Model
{
    protected $connection = 'bmi';
    protected $table = 'ranking_cliente';
    protected $primaryKey='id_ranking_cliente';
    public $timestamps=true;

    // Relación Clientes
    public function clientes() {
        return $this->hasMany('App\bmi\tbl_clientes','cedula_cliente','cedula_cliente'); // Le indicamos que se va relacionar con el atributo cedula_cliente
    }
}
