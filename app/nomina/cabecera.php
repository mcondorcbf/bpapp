<?php

namespace App\nomina;

use Illuminate\Database\Eloquent\Model;

class cabecera extends Model
{
    protected $connection = 'nomina';
    protected $table = 'cabecera';
    protected $primaryKey='id_cabecera';
    public $timestamps=true;
}
