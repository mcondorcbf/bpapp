<?php

namespace App\reportesNuevoSistema\cex;

use Illuminate\Database\Eloquent\Model;

class tbl_dispositivos extends Model
{
    protected $connection = 'apiRest';
    protected $table = 'dispositivos';
    protected $primaryKey='id';
    public $timestamps=true;
}
