<?php

namespace App\ivrs;

use Illuminate\Database\Eloquent\Model;

class tbl_tipo_script extends Model
{
    protected $connection = 'ivrs';
    protected $table = 'tbl_tipo_script';
    protected $primaryKey='id_tipo';
    public $timestamps=false;
}
