<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_accounts extends Model
{
    protected $connection = 'cobefec3';
    protected $table = 'accounts';
    protected $primaryKey='id';
    public $timestamps=true;

    // Relación Campañas
    public function campana() {
        return $this->hasOne('App\reportesNuevoSistema\tbl_campaigns','id','campaign_id');
    }
}
