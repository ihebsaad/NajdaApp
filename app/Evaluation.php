<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Evaluation extends Model
{
    protected $fillable = ['prestataire','gouv','type_prest','priorite','disponibilite','evaluation','specialite'];

}
