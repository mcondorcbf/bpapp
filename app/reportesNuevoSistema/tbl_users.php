<?php

namespace App\reportesNuevoSistema;

use Illuminate\Database\Eloquent\Model;

class tbl_users extends Model
{
    protected $connection = 'cobefec3';
    protected $table = 'users';
    protected $primaryKey='id';
    public $timestamps=true;
}
