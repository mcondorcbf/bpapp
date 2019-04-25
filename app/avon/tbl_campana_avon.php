<?php

namespace App\avon;

use Illuminate\Database\Eloquent\Model;

class tbl_campana_avon extends Model
{
    protected $connection = 'gestionec';
    protected $table = 'capana';
    protected $primaryKey='id';
    public $timestamps=false;
}
