<?php

namespace App\apiRest;

use Illuminate\Database\Eloquent\Model;

class tbl_api_cobefec extends Model
{
    protected $connection='apiRest';
    protected $table='api_cobefec';
    protected $primaryKey='id';
    public $timestamps=true;
}
