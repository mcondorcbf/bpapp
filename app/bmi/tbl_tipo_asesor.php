<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_tipo_asesor extends Model
{
    protected $connection = 'bmi';
    protected $table = 'tipo_asesor';
    protected $primaryKey='id_tipo_asesor';
    public $timestamps=true;

    // Relación Asesores
    public function asesor() {
        return $this->hasMany('App\bmi\tbl_asesor','id_asesor','id_asesor'); // Le indicamos que se va relacionar con el atributo id_asesor
    }
}
