<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Email extends Model
{
    protected $fillable = ['champ','nom','tel','qualite','parent'];

    protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
}
