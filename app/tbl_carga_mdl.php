<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class tbl_carga_mdl extends Model
{
    protected $table='tbl_carga';
    protected $primaryKey='CEDULA';
    public $timestamps=false;
}