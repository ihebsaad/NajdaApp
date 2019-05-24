<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $fillable = ['abbrev','titre','contenu','montant','devise','entree'];

}