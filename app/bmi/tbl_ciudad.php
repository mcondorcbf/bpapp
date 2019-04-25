<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_ciudad extends Model
{
    protected $connection = 'bmi';
    protected $table = 'ciudad';
    protected $primaryKey='id_ciudad';
    public $timestamps=true;

    // Relación Direcciones
    public function direccion() {
        return $this->hasMany('App\bmi\tbl_direccion','id_direccion','id_direccion'); // Le indicamos que se va relacionar con el atributo id_direccion
    }

    // Relación Provincia
    public function provincia() {
        return $this->hasOne('App\bmi\tbl_provincia','id_provincia','id_provincia'); // Le indicamos que se va relacionar con el atributo id_provincia
    }
}
