<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_asesores extends Model
{
    protected $connection = 'bmi';
    protected $table = 'asesores';
    protected $primaryKey='cedula_asesor';
    public $timestamps=true;

    // Relación Asesor
    public function tipo_asesor() {
        return $this->hasOne('App\bmi\tbl_tipo_asesor','id_tipo_asesor','id_tipo_asesor'); // Le indicamos que se va relacionar con el atributo id_tipo_asesor
    }

    // Relación Ranking
    public function ranking_asesor() {
        return $this->hasOne('App\bmi\tbl_ranking_asesor','id_ranking','id_ranking'); // Le indicamos que se va relacionar con el atributo id_ranking
    }
}
