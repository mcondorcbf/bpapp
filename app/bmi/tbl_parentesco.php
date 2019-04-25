<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_parentesco extends Model
{

    protected $connection = 'bmi';
    protected $table = 'parentesto';
    protected $primaryKey='id_parentesco';
    public $timestamps=true;

    // Relación Pariente
    public function pariente() {
        return $this->hasMany('App\bmi\tbl_pariente','id_pariente','id_pariente'); // Le indicamos que se va relacionar con el atributo id_pariente
    }
}
