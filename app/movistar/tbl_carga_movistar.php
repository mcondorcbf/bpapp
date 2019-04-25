<?php

namespace App\movistar;

use Illuminate\Database\Eloquent\Model;

class tbl_carga_movistar extends Model
{
    protected $connection = 'movistar';
    protected $table='tbl_carga_movistar';
    public $timestamps=false;
}
