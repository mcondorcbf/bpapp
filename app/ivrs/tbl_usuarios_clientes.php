<?php

namespace App\ivrs;

use Illuminate\Database\Eloquent\Model;

class tbl_usuarios_clientes extends Model
{
    protected $connection = 'ivrs';
    protected $table = 'tbl_usuarios_clientes';
    protected $primaryKey='id_usuarios_clientes';
    public $timestamps=false;
}
