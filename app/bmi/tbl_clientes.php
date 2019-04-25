<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_clientes extends Model
{
    protected $connection = 'bmi';
    protected $table = 'clientes';
    protected $primaryKey='cedula_cliente';
    public $timestamps=true;

    // Relación Empresa
    public function empresa() {
        return $this->hasOne('App\bmi\tbl_empresa','id_empresa','id_empresa'); // Le indicamos que se va relacionar con el atributo id_empresa
    }

    // Relación Tipo Cliente
    public function tipo_cliente() {
        return $this->hasOne('App\bmi\tbl_tipo_cliente','id_tipo_cliente','id_tipo_cliente'); // Le indicamos que se va relacionar con el atributo cedula_cliente
    }

    // Relación Ranking Cliente
    public function ranking_cliente() {
        return $this->hasOne('App\bmi\tbl_ranking_cliente','id_ranking_cliente','id_ranking'); // Le indicamos que se va relacionar con el atributo id_ranking_cliente
    }

    // Relación Gestiones
    public function ultimaGestion() {
        return $this->hasOne('App\bmi\tbl_gestiones','id_gestion','id_ultima_gestion'); // Le indicamos que se va relacionar con el atributo id_gestion
    }
    // Relación Gestiones
    public function mejorGestion() {
        return $this->hasOne('App\bmi\tbl_gestiones','id_gestion','id_mayor_gestion'); // Le indicamos que se va relacionar con el atributo id_gestion
    }

}
