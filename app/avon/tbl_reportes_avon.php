<?php

namespace App\avon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Translatable;
class tbl_reportes_avon extends Model
{
    protected $table = 'tbl_reportes_avon';
    protected $primaryKey='id';
}
