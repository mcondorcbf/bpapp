<?php

namespace App\predictivos;

use Illuminate\Database\Eloquent\Model;

class prd2_campaign extends Model
{
    protected $connection = 'predictivo2';
    protected $table = 'campaign';
    protected $primaryKey='id';
    public $timestamps=false;
}
