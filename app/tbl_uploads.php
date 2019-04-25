<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_uploads extends Model
{
    public $timestamps = false;

    protected $table = 'tbl_uploads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre_archivo'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
