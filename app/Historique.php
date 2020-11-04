<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Historique extends Model

{
	
  protected $fillable = [
 'description',
      'user',
      'user_id',
      ];
 
}
