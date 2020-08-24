<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Rating extends Model
{
 
 protected $fillable = ['prestataire','prestation', 'disponibilite','raison','ponctualite','reactivite','commentaire','retour' ];

}
