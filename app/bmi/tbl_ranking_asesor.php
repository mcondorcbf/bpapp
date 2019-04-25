<?php

namespace App\bmi;

use Illuminate\Database\Eloquent\Model;

class tbl_ranking_asesor extends Model
{
    protected $connection = 'bmi';
    protected $table = 'ranking_asesor';
    protected $primaryKey='id_ranking';
    public $timestamps=true;
}
