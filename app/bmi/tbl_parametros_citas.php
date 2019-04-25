<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_parametros_citas extends Model
{
    protected $connection = 'bmi';
    protected $table = 'tbl_parametros_citas';
    protected $primaryKey='id_parametros_citas';
    public $timestamps=true;

    // Relación Ranking
    public function ranking_asesor() {
        return $this->hasOne('App\bmi\tbl_ranking_asesor','id_ranking','id_ranking'); // Le indicamos que se va relacionar con el atributo id_ranking
    }
}