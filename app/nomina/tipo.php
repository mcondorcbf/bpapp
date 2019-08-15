<?php

namespace App\nomina;

use Illuminate\Database\Eloquent\Model;

class tipo extends Model
{
    protected $connection = 'nomina';
    protected $table = 'tipo';
    protected $primaryKey='id_tipo';
    public $timestamps=true;
}
