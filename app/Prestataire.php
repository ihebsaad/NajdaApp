<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Prestataire extends Model

{
    protected $fillable = ['id', 'par','updated_at' , 'created_at',

'nom',
'specialite',
'observation',
'priorite',
'typepres',
'gouvernorat',
'ville',
'adresse',
'fax',
'mobile',
'mobile2',
'telephone',
'telephone2',
'email',
'email2',
'email3',
'email4',
'email5',
'annule'

    ];
/*
    protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
*/
}
