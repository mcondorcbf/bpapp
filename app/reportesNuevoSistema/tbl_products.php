<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_products extends Model
{
    protected $connection = 'cobefec3';
    protected $table = 'products';
    protected $primaryKey='id';
    public $timestamps=true;


}
