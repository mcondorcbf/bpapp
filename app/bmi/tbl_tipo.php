<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_tipo extends Model
{
    protected $connection = 'bmi';
    protected $table = 'tbl_tipo';
    protected $primaryKey='id_tipo';
    public $timestamps=true;
}
