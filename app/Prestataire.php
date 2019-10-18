<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Prestataire extends Model

{
    protected $fillable = ['id', 'par','updated_at' , 'created_at',

'name',
'specialite',
'observation_prestataire',
'ordre',
'typepres',
'ville',
'ville_id',
'adresse',
'fax',
'phone_cell',
'phone_cell2',
'phone_home',
'phone_home2',
'mail',
'mail2',
'mail3',
'mail4',
'mail5',
'annule',
'prefixe',
'prenom',
'dossier'


    ];

/*
    protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
*/
}
