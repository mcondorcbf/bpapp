<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_producto extends Model
{
    protected $connection = 'bmi';
    protected $table = 'producto';
    protected $primaryKey='id_producto';
    public $timestamps=true;
}
