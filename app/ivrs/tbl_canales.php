<?php

namespace App\ivrs;

use Illuminate\Database\Eloquent\Model;

class tbl_canales extends Model
{
    protected $connection = 'ivrs';
    protected $table='tbl_canales';
    public $timestamps=false;
}
