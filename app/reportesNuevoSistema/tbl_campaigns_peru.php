<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_campaigns_peru extends Model
{
    protected $connection = 'cobefec3Peru';
    protected $table = 'campaigns';
    protected $primaryKey='id';
    public $timestamps=true;
}
