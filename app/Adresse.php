<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Adresse extends Model

{
	
  protected $fillable = [
'champ',
'type',
'nature',
'remarque',
'parent',
'nom',
'prenom',
'fonction',
'tel',
'fax',
'mail'
    ];
 
}
