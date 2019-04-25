<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_notificaciones extends Model
{
    protected $connection = 'bmi';
    protected $table = 'tbl_notificaciones';
    protected $primaryKey='id_gestion';
    public $timestamps=true;

    protected $guarded =['id'];
}
