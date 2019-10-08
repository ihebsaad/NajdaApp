<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    //
    protected $table = 'seance';
    protected $fillable = ['id','debut','fin','dispatcheur','superviseurmedic','superviseurtech','chargetransport','superviseur'];

/*
    protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }

*/

}