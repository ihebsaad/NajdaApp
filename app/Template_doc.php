<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template_doc extends Model
{
    //
    protected $fillable = ['id','nom','path','template_annulation','template_remplace','champs','qualification','doc_qualification','description'];


}
