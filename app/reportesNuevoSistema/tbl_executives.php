<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_executives extends Model
{
    protected $connection = 'cobefec3';
    protected $table = 'executives';
    protected $primaryKey='id';
    public $timestamps=true;

    // Relación Users
    public function usuario() {
        return $this->hasOne('App\reportesNuevoSistema\tbl_users','id','user_id');
    }
}
