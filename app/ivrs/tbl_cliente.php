<?php

namespace App\ivrs;

use Illuminate\Database\Eloquent\Model;

class tbl_cliente extends Model
{
    protected $connection = 'ivrs';
    protected $table = 'tbl_cliente';
    protected $primaryKey='id_cliente';
    public $timestamps=false;
}
