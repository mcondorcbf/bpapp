<?php

namespace App\ivrs;

use Illuminate\Database\Eloquent\Model;

class tbl_id_carga extends Model
{
    protected $connection = 'ivrs';
    protected $table = 'tbl_id_carga';
    protected $primaryKey='id_carga';
    public $timestamps=true;

    // Relación Campania
    public function campaniaIvr() {
        return $this->hasOne('App\ivrs\tbl_campania','id_campania','id_campania'); // Le indicamos que se va relacionar con el atributo id_campania
    }
}
