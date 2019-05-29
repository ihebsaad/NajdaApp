<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Prestation extends Model

{
    protected $fillable = ['prestataire_id','type_prestations_id','dossier_id'];

}
