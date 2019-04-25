<?php

namespace App\ivrs;

use Illuminate\Database\Eloquent\Model;

class tbl_script extends Model
{
    protected $connection = 'ivrs';
    protected $table = 'tbl_script';
    protected $primaryKey='id_script';
    public $timestamps=true;
}
