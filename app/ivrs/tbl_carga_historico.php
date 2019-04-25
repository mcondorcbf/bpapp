<?php

namespace App\ivrs;

use Illuminate\Database\Eloquent\Model;

class tbl_carga_historico extends Model
{
    protected $connection = 'ivrs';
    protected $table = 'tbl_carga_historico';
    protected $primaryKey='id';
    public $timestamps=true;
}
