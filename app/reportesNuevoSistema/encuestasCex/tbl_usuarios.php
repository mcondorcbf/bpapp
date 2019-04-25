<?php

namespace App\reportesNuevoSistema\encuestasCex;

use Illuminate\Database\Eloquent\Model;

class tbl_usuarios extends Model
{
    protected $connection = 'encuestascex';
    protected $table = 'usuarios';
    protected $primaryKey='id_usuario';
    public $timestamps=true;

    // Relación con rol
    public function rol() {
        return $this->hasOne('App\reportesNuevoSistema\encuestasCex\tbl_rol','id_rol','id_rol'); // Le indicamos que se va relacionar con el atributo id_rol
    }
}