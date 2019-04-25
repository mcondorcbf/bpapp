<?php

namespace App\reportesNuevoSistema\encuestasCex;

use Illuminate\Database\Eloquent\Model;

class tbl_preguntas extends Model
{
    protected $connection = 'encuestascex';
    protected $table = 'preguntas';
    protected $primaryKey='id_pregunta';
    public $timestamps=true;

    // Relación con categoria
    public function categoria() {
        return $this->hasOne('App\reportesNuevoSistema\encuestasCex\tbl_categorias','id_categoria','id_categoria'); // Le indicamos que se va relacionar con el atributo id_categoria
    }
}
