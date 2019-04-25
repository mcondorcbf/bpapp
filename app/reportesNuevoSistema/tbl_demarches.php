<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_demarches extends Model
{
    protected $connection = 'cobefec3';
    protected $table = 'demarches';
    protected $primaryKey='id';
    public $timestamps=true;

    // Relación Agentes
    public function agente() {
        return $this->hasOne('App\reportesNuevoSistema\tbl_agents','id','agent_id');
    }
    // Relación Cuentas
    public function cuenta() {
        return $this->hasOne('App\reportesNuevoSistema\tbl_accounts','id','account_id');
    }
}
