<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_provincia extends Model
{
    protected $connection = 'bmi';
    protected $table = 'provincia';
    protected $primaryKey='id_provincia';
    public $timestamps=true;

    // Relación Ciudad
    public function ciudad() {
        return $this->hasMany('App\bmi\tbl_ciudad','id_ciudad','id_ciudad'); // Le indicamos que se va relacionar con el atributo id_ciudad
    }
}
