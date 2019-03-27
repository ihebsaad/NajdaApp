<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachement extends Model
{
    //
    protected $fillable = ['id','nom','type','parent','path'];

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }

}
