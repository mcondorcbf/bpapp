<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_clientes2 extends Model
{
    protected $connection = 'bmi';
    protected $table = 'clientes2';
    protected $primaryKey='id2';
    public $timestamps=true;
}
