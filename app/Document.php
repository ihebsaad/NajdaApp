<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    protected $fillable = ['id','dossier','titre','description','emplacement'];

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }

}
