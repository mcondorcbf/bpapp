<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_accion extends Model
{
    protected $connection = 'bmi';
    protected $table = 'tbl_accion';
    protected $primaryKey='id_accion';
    public $timestamps=true;


}
