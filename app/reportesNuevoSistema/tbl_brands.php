<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_brands extends Model
{
    protected $connection = 'cobefec3';
    protected $table = 'brands';
    protected $primaryKey='id';
    public $timestamps=true;
}
