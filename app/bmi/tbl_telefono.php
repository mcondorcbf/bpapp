<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_telefono extends Model
{
    protected $connection = 'bmi';
    protected $table = 'telefono';
    protected $primaryKey='id_telefono';
    public $timestamps=true;
}
