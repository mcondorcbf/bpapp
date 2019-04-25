<?php

namespace App\ivrs;

use Illuminate\Database\Eloquent\Model;

class tbl_carga extends Model
{
    protected $connection = 'ivrs';
    protected $table = 'tbl_carga';
    protected $primaryKey='id';
    public $timestamps=true;

    // Relación Carga
    public function cargaIvr() {
        return $this->hasOne('App\ivrs\tbl_id_carga','id_carga','id_carga'); // Le indicamos que se va relacionar con el atributo carga
    }

    // Relación Script
    public function scriptIvr() {
        return $this->hasOne('App\ivrs\tbl_script','id_script','id_script'); // Le indicamos que se va relacionar con el atributo id_script
    }
}
