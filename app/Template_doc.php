<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template_doc extends Model
{
    //
    protected $fillable = ['id','nom','path','path_m','path_mi','template_annulation','template_annulation_m','template_annulation_mi','template_remplace','template_remplace_m','template_remplace_mi','template_modif','template_modif_m','template_modif_mi','template_html','champs','qualification','doc_qualification','description'];


}
