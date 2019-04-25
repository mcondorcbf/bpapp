<?php

namespace App\ivrs;

use Illuminate\Database\Eloquent\Model;

class usuarios_varios_clientes extends Model
{
    protected $connection = 'ivrs';
    protected $table = 'usuarios_varios_clientes';
    public $timestamps=false;
}
