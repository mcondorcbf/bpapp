<?php

namespace App\avon;

use Illuminate\Database\Eloquent\Model;

class tbl_campana_peru_avon extends Model
{
    protected $connection = 'gestionpe';
    protected $table = 'capana';
    protected $primaryKey='id';
    public $timestamps=false;
}
