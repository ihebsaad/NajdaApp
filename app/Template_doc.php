<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template_doc extends Model
{
    //
    protected $fillable = ['id','nom','path','template_annulation','champs','qualification','doc_qualification','description'];


}
