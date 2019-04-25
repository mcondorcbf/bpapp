<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_agents extends Model
{
    protected $connection = 'cobefec3';
    protected $table = 'agents';
    protected $primaryKey='id';
    public $timestamps=true;

    // Relación Agentes
    public function usuario() {
        return $this->hasOne('App\reportesNuevoSistema\tbl_users','id','user_id');
    }
}
