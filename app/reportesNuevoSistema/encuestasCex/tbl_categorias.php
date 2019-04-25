<?php

namespace App\reportesNuevoSistema\encuestasCex;

use Illuminate\Database\Eloquent\Model;

class tbl_categorias extends Model
{
    protected $connection = 'encuestascex';
    protected $table = 'categorias';
    protected $primaryKey='id_categoria';
    public $timestamps=true;
}
