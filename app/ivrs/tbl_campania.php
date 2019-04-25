<?php

namespace App\ivrs;

use Illuminate\Database\Eloquent\Model;

class tbl_campania extends Model
{
    protected  $connection='ivrs';
    protected $table = 'tbl_campania';
    protected $primaryKey='id_campania';
    public $timestamps=false;

    // Relación Cliente
    public function clienteIvr() {
        return $this->hasOne('App\ivrs\tbl_cliente','id_cliente','id_cliente'); // Le indicamos que se va relacionar con el atributo id_cliente
    }
}
