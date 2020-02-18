<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachement extends Model
{
    //
    protected $fillable = ['id','nom','type','entree_id','path','path_org','facturation','envoye_id','parent','boite','dossier','description','filesize','fullpath','user','created_at'];

  /*  protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
*/

}
