<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_products_peru extends Model
{
    protected $connection = 'cobefec3Peru';
    protected $table = 'products';
    protected $primaryKey='id';
    public $timestamps=true;
}
