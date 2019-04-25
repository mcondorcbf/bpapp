<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_campaigns extends Model
{
    protected $connection = 'cobefec3';
    protected $table = 'campaigns';
    protected $primaryKey='id';
    public $timestamps=true;

    // Relación Productos
    public function producto() {
        return $this->hasOne('App\reportesNuevoSistema\tbl_products','id','product_id');
    }
}
