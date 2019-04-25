<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_codigo_cancelacion extends Model
{
    protected $connection = 'predictivo2';
    protected $table='tbl_codigo_cancelacion';
    protected $primaryKey='id';
    public $timestamps=false;
}
