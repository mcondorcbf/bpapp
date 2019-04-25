<?php

namespace App\predictivos;

use Illuminate\Database\Eloquent\Model;

class prd2_call_center extends Model
{
    protected $connection = 'predictivo2';
    protected $table = 'calls';
    protected $primaryKey='id';
    public $timestamps=false;
}
