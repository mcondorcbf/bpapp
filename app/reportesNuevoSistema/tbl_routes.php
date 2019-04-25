<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_routes extends Model
{
    protected $connection = 'cobefec3';
    protected $table = 'routes';
    protected $primaryKey='id';
    public $timestamps=true;
}
