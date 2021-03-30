@extends('layouts.mainlayout')
{{-- Page title --}}
@section('title')
    @parent
@stop
<?php
use App\Http\Controllers\DossiersController;use App\Tag ;
use App\Dossier ;
use App\Notification ;
$dossiers = Dossier::get();

use App\Attachement ;
use App\Http\Controllers\AttachementsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\TagsController;
Use App\Common;
Use App\Adresse;
Use App\USer;
?>
{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/custom_css/layout_responsive.css') }}">
    <link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />
<script src="{{ URL::asset('public/js/upload_files/vpb_uploader.js') }}"> </script>

@stop
@section('content')

    <?php
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;

 /*   function SstartsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }*/
?>

<div class="panel panel-default panelciel " style="">
 @if(session()->has('AffectNouveauDossier'))
    <div class="alert alert-success">
       <center> <h4>{{ session()->get('AffectNouveauDossier') }}</h4></center>
    </div>
  @endif
        <div class="panel-heading" style=""   >
                     <div class="row">
                        <div  style=" padding-left: 0px;color:black;font-weight: bold ;">
                            <h4 class="panel-title  " > <label for="sujet" style=" ;font-size: 15px;">Sujet :</label>  <?php $sujet=$entree['sujet'];

                            if(Common::SstartsWith($sujet,"=?utf") || Common::SstartsWith($sujet,"=?windows") ||Common::SstartsWith($sujet,"=?UTF") || Common::SstartsWith($sujet,"=?WIND")   ) {
                                    $sujet=  iconv_mime_decode( nl2br(strval(utf8_encode($sujet)) )  );
                                }
                                echo $sujet;
                            ?><span id="hiding" class="pull-right">
         <i style="color:grey;margin-top:10px"class="fa fa-2x fa-fw clickable fa-chevron-down"></i>
            </span></h4>
                        </div>
                    </div>
                        <div class="row" style="padding-right: 10px;margin-top:10px" id="emailbuttons">
                            <div class="pull-right" style="margin-top: 0px;"><?php $iddossier=$entree['dossierid'] ; ?>
                                @if (!empty($entree->dossier))
                                    <button class="btn btn-sm btn-default"><b><a style="color:black" href="<?php echo $urlapp.'/dossiers/view/'.$iddossier;?>">REF: {{ $entree['dossier']   }} - <?php echo  DossiersController::FullnameAbnDossierById($iddossier); ?></a></b></button>
                                @endif
                                @if (empty($entree->dossier))
                                     <a   class="btn btn-md btn-success"   href="{{route('dossiers.create',['identree'=> $entree['id']]) }}"  > <i class="fas fa-folder-plus"></i> Créer un Dossier</a>

                                @endif

                                    <button id="afffolder" class="btn   " style="width:180px;background-color: #c5d6eb;color:#333333;"  data-toggle="modal" data-target="#affectfolder"><b><i class="fas fa-folder"></i>  Re-Dispatcher</b></button>

                                <?php    $seance =  DB::table('seance')
                                    ->where('id','=', 1 )->first();
                                    $disp=$seance->dispatcheur ;

                                    $iduser=Auth::id();
                                  //  if ($iduser==$disp) { ?>
                                    <?php if($entree['type']!=="tel" || $entree['par']===null ) {?>
                                    <a  href="{{action('EntreesController@archiver', $entree['id'])}}" style="color:black" class="btn btn-warning btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Archiver" >
                                  <span class="fa fa-fw fa-archive"></span> Archiver
                                </a>
                                   <?php /* if ($entree['type'] != 'tel'){ ?>
                                    <a  href="{{action('EntreesController@spam', $entree['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Marquer comme SPAM" >
                                        <span class="fas fa-exclamation-triangle"></span> SPAM
                                    </a>
                                      <?php //} ?>

                                    <?php } */ ?>
                                <?php if ($entree['notif']!=1 ) { ?>
                                    <a onclick="checkComment()"  class="btn btn-info btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Marquer comme traité" >
                                        <span class="fa fa-fw fa-check"></span> Traité
                                    </a>
                                <?php } ?>
								 <?php if ($entree['accuse']!=1 ) { ?>
                                    <a  onclick="accuse();" class="btn btn-info btn-sm btn-responsive " >
                                        <span class="fa fa-fw fa-envelope"></span> Accusé
                                    </a>
                                <?php } ?>

								   
                                <?php if ($entree->dossierid >0 ){ ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-share"></i> Transférer <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="{{route('emails.envoimailenreg',['id'=>$dossier->id,'type'=> 'client','prest'=> 0,'entreeid'=>$entree->id,'envoyeid'=>0 ])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                                                Au client </a>
                                        </li>
                                        <li>
                                            <a href="{{route('emails.envoimailenreg',['id'=>$dossier->id,'type'=> 'prestataire','prest'=> 0 ,'entreeid'=>$entree->id,'envoyeid'=>0 ])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                                                À l'intervenant </a>
                                        </li>
                                        <li>
                                            <a href="{{route('emails.envoimailenreg',['id'=>$dossier->id,'type'=> 'assure','prest'=> 0 ,'entreeid'=>$entree->id,'envoyeid'=>0  ] )}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                                                À l'assuré </a>
                                        </li>

                                    </ul>
                                </div>
                                <?php } ?>
                                 <?php } ?>
<?php if ($entree['type']!=="tel" ){?>

                                    <a  href="{{action('EntreesController@destroy3', $entree['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                        <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                                    </a>

<?php } ?>

                            </div>
                        </div>
                 </a>
        </div>
        <div id="emailhead" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="panel-body">
              <?php
 $emetteur= explode(' ', $entree['emetteur']);
              $adressecomm=Adresse::where("champ",$emetteur)->first();
              $usercom=User::where("id",$entree['par'])->first();
              ?>
                <div class="row" style="font-size:12px;">
                        <div class="col-sm-4 col-md-4 col-lg-4"style=" padding-top: 4px; ">
                          <?php if ($entree['type']==="tel" ){?>
                            <span><b>Emetteur: </b>{{ $entree['emetteur']." (".$adressecomm['prenom']." ".$adressecomm['nom'].")"  }}</span>
 <?php } else {?>
<span><b>Emetteur: </b>{{ $entree['emetteur'] }}</span>
<?php } ?>
                        </div>
                    <div class="col-sm-4 col-md-4 col-lg-4" style=" padding-left: 0px; ">
                       <?php if ($entree['type']==="tel" ){?>
                        <span><b>À : </b>{{ $entree['destinataire']." (".$usercom['name']." ".$usercom['lastname'].")" }}</span>

                         <?php } else {?>
                          <span><b>À : </b>{{ $entree['destinataire']  }}</span>
<?php } ?>

                    </div>
                        <div class="col-sm-4 col-md-4 col-lg-4 " style="padding-right: 0px;">


                            <span class="pull-right"><b>Date: </b><?php if ($entree['type']=='email' || ($entree['type']=='tel' && $entree['par']!==null )  ){echo  date('d/m/Y H:i', strtotime( $entree['reception']  )) ; 

if($entree['type']=='tel')
{
  function convert($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}
?>

 <span  class="pull-right"><b>,Durée: </b><?php echo  convert($entree['duration'] );?> </span>
  <?php
}
else
{
  echo "";
}
}
else {echo  date('d/m/Y H:i', strtotime( $entree['created_at']  )) ; }
?>
                          


                   
                        </span>
                            <?php 
                                // verifier si l'entree possede de notification et la marque comme lu
                                $identr=$entree['id'];
                                $havenotif=NotificationsController::havenotification($identr);
                                if ($havenotif)
                                {
                                    // marker comme lu avec la date courante
                                    $date = date('Y-m-d g:i:s');
                                    Notification::where('id', $havenotif)->update(array('read_at' => $date));
                                }
                            ?>
                        </div>
                </div>
            </div>
        </div>

</div>
<div class="panel panel-default panelciel " >
        <!--<div class="panel-heading" style="cursor:pointer" data-toggle="collapse" data-parent="#accordion-cat-1" href="#emailcontent" class="" aria-expanded="true">-->
        <div class="panel-heading" data-parent="#accordion-cat-1" href="#emailcontent" >
                <a >
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6"style=" padding-left: 0px; ">
                            <h4 class="panel-title"> <label for="sujet" style="font-size: 15px;"><?php if ( $entree['type']=='fax') {echo 'F A X';}else {?>Contenu<?php }?></label></h4>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6" style="padding-right: 0px;">
                        </div>
                    </div>        
                 </a>
        </div>
  <?php
                                            // get attachements info from DB
    $attachs = Attachement::get()->where('parent', '=', $entree['id'] )->where('boite','0');
    $nbattachs = Attachement::where('parent', '=', $entree['id'] )->where('boite','0')->count();

                                                                                      ?>
        <div id="emailcontent" class="panel-collapse collapse in" aria-expanded="true" style="min-height:250px">

            <div class="panel-body" id="emailnpj">
                <div class="row">
                   <ul id="mailpiece" name="mailpiece" class="nav nav-pills">
                        <li data-type="email" data-identreeattach="<?php echo $entree['id'] ?>" class="<?php if($entree['contenu']!=null){echo 'active ';} ?>" >
                           <?php if ( $entree['type']=='fax') {}else {  if ( $entree['type']!='tel') { ?><a   href="#mailcorps" data-toggle="tab" aria-expanded="true">Corps HTML du mail</a><?php } }?>
                       </li>
                       <li data-type="email" data-identreeattach="<?php echo $entree['id'] ?>" class="<?php if($entree['contenu']==null){echo 'active ';} ?> " >
                           <?php if ( $entree['type']=='fax') {}else {  if ( $entree['type']!='tel') { ?><a  href="#mailcorps2" data-toggle="tab" aria-expanded="true">Texte Brute</a><?php } }?>
                       </li>
                       <?php /* if ( $entree['type']!='fax') { ?>
                       <li class=" " >
                              <a href="#txtcorps" data-toggle="tab" aria-expanded="true"> Texte Brut</a>
                       </li>
                       <?php } */
             $i=0;
                       if ( $entree['type']!='tel') { 
               if ( $nbattachs   > 0)
            {
             foreach ($attachs as $att)
             {
                      
                 if ( ($att->type =="pdf") ||($att->type =="png") ||($att->type =="jpg") || ($att->type =="jpeg") || ($att->type =="gif")||($att->type =="bmp")        )
         {       $i++;

       
       ?>
                                <li data-type="piecejointe" data-identreeattach="<?php echo $att['id'] ?>">
                                    <a  class=" " href="#pj<?php echo $i; ?>" data-toggle="tab" aria-expanded="false">PJ<?php echo $i; ?></a>
                                </li>
                      
      <?php } //extension
                        
             }  //foreach 
             }/// nb attach
             } //tel
             ?>
                    </ul>
                    
                    <div id="myTabContent" class="tab-content" style="background: #ffffff">
                       <?php if ( $entree['type']!='fax') { ?>
                           <div class="tab-pane fade <?php if($entree['contenu']!=null){echo 'active in';} ?> " id="mailcorps" style="">
                               <section>
                               <p  id="mailtext" style=" line-height: 25px;"><?php
                                              if($entree['contenu']!= null)
                                              {$content= nl2br($entree['contenu']) ;

                                              ?>
                                            <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
                                            <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

                                            <?php  $cont=  str_replace($search,$replace, $content); ?>
                                   <?php

                                   echo $cont.'<br><br>';

                                   if($entree['type']== "tel")
                                   {
                                    if($entree['par']!== null)
                                    {
 echo '<b>Média : </b>'?>
  <audio style="width:300px;"controls>
  <source src="<?php  echo  $entree["path"] ; ?>" type="audio/wav">
 Your browser does not support the audio element.
</audio><br><br><br>
                                  <?php  }
                                    else{
                                       echo '<b>Média : </b>'. $entree['contenutxt'].'<br><br><br>';

                                    }
                                  

                                   echo '<b>Description : </b>'. $entree['commentaire'].'<br>';
                                   }

                                  ?></p>
                               </section>

                           <?php } ?>

                                        </div>

                           <div class="tab-pane fade  <?php if($entree['contenu']==null){echo 'active in';} ?>" id="mailcorps2" style="">
                               <section>
                               <p  id="mailtext2" style=" line-height: 25px;"><?php
                                   if($entree['contenutxt']!= null)
                                   {$content2= nl2br($entree['contenutxt']) ;

                                   ?>
                                   <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
                                   <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

                                   <?php  $cont2=  str_replace($search,$replace, $content2); ?>
                                   <?php  echo $cont2; ?></p>
                               </section>

                           <?php }; ?>
                           </div><?php } ?>


                           <?php // } ?>
                           <?php /* if ( $entree['type']!='fax') { ?>
                           <div class="tab-pane fade   in" id="txtcorps" style="">
                               <p  id="mailtext2" style=" line-height: 25px;"><?php  $contenttxt= $entree['contenutxt'] ; ?>
                                   <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
                                   <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

                                   <?php  $cont2=  str_replace($search,$replace, $contenttxt); ?>
                                   <?php // $cont=  str_replace("invoice","<b>invoice</b>", $content); ?>
                                   <?php  echo $cont2; ?></p>
                           </div><?php } */ ?>

                         @if ($nbattachs > 0)
                
                                            @if (!empty($attachs) )
                                            <?php $i=1; ?>
                                            @foreach ($attachs as $att)
                                            <?php if ( ($att->type =="pdf") ||($att->type =="png") ||($att->type =="jpg") || ($att->type =="jpeg") || ($att->type =="gif")||($att->type =="bmp")        )
                      { ?>

                        <div class="tab-pane fade in <?php  if ( ($entree['type']=='fax')&&($i==1)) {echo 'active';}?>" id="pj<?php echo $i; ?>">

                                                    <h4><b style="font-size: 13px;">{{ $att->nom }}</b> (<a style="font-size: 13px;" href="<?php if($att->type =="pdf"){if($att->path_org){ echo URL::asset('storage'.$att->path_org);}else{echo URL::asset('storage'.$att->path);} }else{ echo URL::asset('storage'.$att->path); }?>" download>Télécharger</a>)</h4>

                                    @switch($att->type)                                  
                                    @case('docx')
                                    @case('doc')
                                    @case('dot')
                                    @case('dotx')
                                    @case('docm')
                                    @case('odt')
                                    @case('pot')
                                    @case('potm')
                                    @case('pps')
                                    @case('ppsm')
                                    @case('ppt')
                                    @case('pptm')
                                    @case('pptx')
                                    @case('ppsx')
                                    @case('odp')
                                    @case('xls')
                                    @case('xlsx')
                                    @case('xlsm')
                                    @case('xlsb')
                                    @case('ods')
                                    @case('wri')
                                    @case('602')
                                    @case('txt')
                                    @case('sdw')
                                    @case('sgl')
                                    @case('wpd')
                                    @case('vor')
                                    @case('wps')
                                    @case('html')
                                    @case('htm')
                                    @case('jdt')
                                    @case('jtt')
                                    @case('hwp')
                                    @case('pdb')
                                    @case('pages')
                                    @case('cwk')
                                    @case('rtf')
                                    @case('gnumeric')
                                    @case('numbers')
                                    @case('dif')
                                    @case('gnm')
                                    @case('wk1')
                                    @case('wks')
                                    @case('123')
                                    @case('wk3')
                                    @case('wk4')
                                    @case('xlw')
                                    @case('xlt')
                                    @case('wk3')
                                    @case('pxl')
                                    @case('wb2')
                                    @case('wq1')
                                    @case('wq2')
                                    @case('sdc')
                                    @case('vor')
                                    @case('slk')
                                    @case('wk3')
                                    @case('xlts')
                                    @case('svg')
                                    @case('odg')
                                    @case('odp')
                                    @case('kth')
                                    @case('key')
                                    @case('pcd')
                                    @case('sda')
                                    @case('sdd')
                                    @case('sdp')
                                    @case('potx')
                                        
                                                          
                                    @break

                                                        @case('pdf')
                                                    <?php

                                                      $fact=$att->facturation;
                                                    if ($fact!='')
                                                    {
                                                        echo '<span class="pdfnotice"> Ce document contient le(s) mots important(s) suivant(s) : <b>'.$fact.'</b></span>';
                                                    }

                                                    ?>

                                                            <iframe src="{{ URL::asset('storage'.$att->path) }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                                            @break

                                                        @case('jpg')
                                                        @case('jpeg')
                                                        @case('gif')
                                                        @case('png')
                                                        @case('bmp')
                                                            <img src="{{ URL::asset('storage'.$att->path) }}" class="mx-auto d-block" style="max-width: 100%!important;"> 
                                                            @break
                                                               
                                                        @default
                                                            <span>Type de fichier non reconnu ... </span>
                                                    @endswitch
                                                    
                                                </div>
                                                <?php $i++; 
                      }
                        ?>
                                            @endforeach

                                            @endif

                                        @endif
                    </div>
                </div>
            </div>
                                        <form method="post">
                                                    {{ csrf_field() }}
                                                    <input id="entreeid" type="hidden" name="entree" value="<?php echo $entree['id']; ?>" />
                                                    <input id="urladdtag" type="hidden" name="urladdtag" value="{{ route('tags.addnew') }}" />
                                                    <input id="urldeletetag" type="hidden" name="urldeletetag" value="{{ route('tags.deletetag') }}" />
                                        </form>
        </div>

<?php if($entree['type']!=="tel") {?>
        <center> <button style="margin-bottom:15px" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#annulerAttenteReponse"> Annuler attente de réponse</button></center>
      <?php }?>
</div>

<style>
    .invoice{background-color: #fad9da;padding:1px;}
    label{font-weight:bold;}
</style>

<style>
.pdfnotice{color:red;font-weight: 600;margin-top:10px;margin-bottom:10px;}
    </style>

<?php use \App\Http\Controllers\UsersController;
use \App\Http\Controllers\EntreesController;
$users=UsersController::ListeUsers();

 $CurrentUser = auth()->user();

 $iduser=$CurrentUser->id;

?>

 <?php use \App\Http\Controllers\ActionController;
    if (($dossier)) {
$actionsReouRap=ActionController::ListeActionsRepOuRap($dossier->id);
         
          
       /*echo($actionsReouRap);*/
 ?>


<style>
td {border: 1px #DDD solid; padding: 5px; cursor: pointer;}

.selected {
    background-color: brown;
    color: #FFF;
}
</style>


    <!-- Modal Envoi Mail -->
    <div class="modal  " id="sendmail" >
        <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal7">Accusé de réception d’un mail de demande de prestations/services  </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

    <form  enctype="multipart/form-data" id="theform" method="POST" action="{{action('EmailController@send')}}"    onsubmit="return checkForm(this);"  >
        {{ csrf_field() }}

        <input id="dossier" type="hidden" class="form-control" name="dossier"  value="{{$dossier->id}}" />
        <input id="envoye" type="hidden" class="form-control" name="envoye"  value="" />
        <input id="brsaved" type="hidden" class="form-control" name="brsaved"  value="0" />
        <input id="accuse" type="hidden" class="form-control" name="accuse"  value="1" />
        <input id="identree" type="hidden" class="form-control" name="entree"  value="<?php echo $entree['id'] ; ?>" />


        <?php
		 $langue='francais' ;
        $from='24ops@najda-assistance.com';
		if($iddossier>0){
			$clientid = app('App\Http\Controllers\DossiersController')->ClientDossierById($iddossier);
$dossiersigent=Dossier::where('id',$iddossier)->first();
if($dossiersigent['type_affectation']==="Najda")
{
$entite="Najda Assistance";
$from = '24ops@najda-assistance.com';
}
 if($dossiersigent['type_affectation']==="MEDIC")
{
$entite="Medic' Multiservices";

$from ='assistance@medicmultiservices.com';

}
 if($dossiersigent['type_affectation']==="VAT")
{
$entite="Voyages Assistance Tunisie";

$from ='hotels.vat@medicmultiservices.com';

}
if($dossiersigent['type_affectation']==="Medic International")
{
$entite="Medic’ International";

$from='operations@medicinternational.tn';

}
if($dossiersigent['type_affectation']==="Najda TPA")
{
$entite="Najda TPA";

$from='tpa@najda-assistance.com';

}
if($dossiersigent['type_affectation']==="Transport Najda")
{
$entite="Najda Transport";
$from='taxi@najda-assistance.com';


}
if($dossiersigent['type_affectation']==="Transport MEDIC")
{
$entite="Medic' Multiservices";

$from='ambulance.transp@medicmultiservices.com';

}
if($dossiersigent['type_affectation']==="Transport VAT")
{
$entite="Voyages Assistance Tunisie";

$from='vat.transp@medicmultiservices.com';

}
if($dossiersigent['type_affectation']==="X-Press")
{
$entite="X-Press Remorquage";

$from='x-press1@najda-assistance.com';

}

        $langue = app('App\Http\Controllers\ClientsController')->ClientChampById('langue1',$clientid);
$adresses=Adresse::where('parent',$clientid)->where('nature','email')->get();

		}

        ?>
        <input type="hidden"   name="from" id="from" value="<?php echo $from; ?>" />


        <div class="row">

            <label for="destinataire">Adresse:</label>
            <div class="row">
                <div class="col-md-10">
                    
 <select id="libre" style="width:100%" required  class="form-control" name="libre[]"  multiple   >
                            
                            <option value="<?php echo $entree['emetteur'] ;?>"><?php echo $entree['emetteur'] ;?></option>
                             @foreach($adresses as $adrs)
<?php if ($adrs['champ']!==$entree['emetteur']) {?>
                                <option value="<?php echo $adrs['champ'] ;?>"><?php echo $adrs['champ'] ;?></option>
<?php }?>
                            @endforeach
                        </select>

                </div>
                <div class="col-md-2">
                    <i id="emailso" onclick="visibilite('autres')" class="fa fa-lg fa-arrow-circle-down" style="margin-right:10px"></i> (cc,cci)
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-top:10px;">
            <div id="autres" class="row"  style="display:none " >
                <div  class="row"  style="margin-bottom:10px" >
                    <div class="col-md-2">
                        <label for="cc">CC:</label>
                    </div>
                    <div class="col-md-10">
                        <select id="cc" style="width:100%"   class="itemName form-control" name="cc[]" multiple   >
                            <option></option>
                            <option value="vat@medicmultiservices.com">vat@medicmultiservices.com</option>
                            <option value="fact.vat-groupe@najda-assistance.com">fact.vat-groupe@najda-assistance.com</option>
                            <option value="finances@medicmultiservices.com">finances@medicmultiservices.com</option>
                            <option value="dirops@najda-assistance.com">dirops@najda-assistance.com</option>
                            <option value="controle1@medicmultiservices.com">controle1@medicmultiservices.com</option>
                            <option value="smq@medicmultiservices.com">smq@medicmultiservices.com</option>
                            <option value="chef.plateau@najda-assistance.com">chef.plateau@najda-assistance.com</option>
                            <option value="mohsalah.harzallah@gmail.com">mohsalah.harzallah@gmail.com</option>
                            <option value="mahmoud.helali@gmail.com">mahmoud.helali@gmail.com</option>
                        </select>
                    </div>
                </div>
                <div  class="row"  style="margin-bottom:10px" >
                    <div class="col-md-2">
                        <label for="cci">CCI:</label>
                    </div>
                    <div class="col-md-10">
                        <select id="cci"  style="width:100%"   class="itemName form-control " name="cci[]" multiple  >
                            <option></option>
                            <option value="vat@medicmultiservices.com">vat@medicmultiservices.com</option>
                            <option value="voyages.assistance.tunisie@gmail.com">voyages.assistance.tunisie@gmail.com</option>
                            <option value="fact.vat-groupe@najda-assistance.com">fact.vat-groupe@najda-assistance.com</option>
                            <option value="finances@medicmultiservices.com">finances@medicmultiservices.com</option>
                            <option value="dirops@najda-assistance.com">dirops@najda-assistance.com</option>
                            <option value="controle1@medicmultiservices.com">controle1@medicmultiservices.com</option>
                            <option value="smq@medicmultiservices.com">smq@medicmultiservices.com</option>
                            <option value="chef.plateau@najda-assistance.com">chef.plateau@najda-assistance.com</option>
                            <option value="nejib.karoui@gmail.com">nejib.karoui@gmail.com </option>
                            <option value="mohsalah.harzallah@gmail.com">mohsalah.harzallah@gmail.com</option>
                            <option value="mahmoud.helali@gmail.com">mahmoud.helali@gmail.com</option>
                            <option value="facturation.vat@medicmultiservices.com">facturation.vat@medicmultiservices.com</option>

                        </select>
                    </div>
                </div>
            </div>
        </div>

        <?php   ?>
 <?php

                        $subscriber_name = app('App\Http\Controllers\DossiersController')->ChampById('subscriber_name',$dossiersigent['id']);
                        $subscriber_lastname = app('App\Http\Controllers\DossiersController')->ChampById('subscriber_lastname',$dossiersigent['id']);

                        if ($from=='tpa@najda-assistance.com') {
                            $nomabn = $subscriber_name . ' ' . $subscriber_lastname;
                        }else{
                            $nomabn = $subscriber_name ;
                        }

                        if ($langue=='francais'){
                        $sujet=  $nomabn.'  - V/Réf: '.$dossiersigent['reference_customer'] .' - N/Réf: '.$dossiersigent['reference_medic'];

                        }else{
                        $sujet=  $nomabn.'  - Y/Ref: '.$dossiersigent['reference_customer'] .' - O/Ref: '.$dossiersigent['reference_medic'] ;

                        }
                        ?>
        <div class="form-group">
            <label for="sujet">Sujet :</label>
            <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $sujet; ?>"/>

        </div>


        <div class="form-group">
            <label for="description">Description :</label>
            <input id="description" type="text" class="form-control" name="description" id="description" required/>
        </div>



        <div class="form-group ">
            <label for="contenu">Contenu:</label>
            <div class="editor" >
                <textarea style="min-height: 280px;" id="contenu" type="text"  class="textarea tex-com" placeholder="Contenu de l'email ici" name="contenu" required  >
			<?php if ($langue=='francais'){  ?>
				Bonjour de  <?php echo $entite  ?><br>
				Nous accusons bonne réception de votre dernier mail demandant le(s) service(s) suivants(s) :<br>
			<?php	
			
			}else{ ?>
				Hello from  <?php echo $entite ?><br>
				We acknowledge receipt of your last email requesting the following service(s)<br>	
					
			<?php	}

			$missions=\App\Mission::where('id_entree',$entree['id'] )->get();
			 echo '<ul>' ;
			foreach($missions as $miss){
$commentaire=" (".$miss->commentaire.")";
				echo  '<li><b>' .$miss->nom_type_miss.$commentaire.'</b></li>' ;
			}
		  echo '</ul>' ;
 if ($langue=='francais'){  ?>
				
				Nous en prenons bonne note et reviendrons vers vous pour vous tenir informés au fur et à mesure de l’avancement dans la réalisation du (des) service(s) demandé(s).

			<?php	
			
			}else{ ?>
				
				We are checking it and we will revert back to you and keep you informed as we establish the organization of the requested service(s).
<br>
*This is an automated email, the services are noted in French as in our management system.
					
			<?php	}
			?>
				
				</textarea>
            </div>
        </div>
        <div class="form-group form-group-default">
            <label>Attachements Externes <span style="color:red;">(la taille totale de fichiers ne doit pas dépasser 25 Mo)</span></label>
            <!--<input  class="btn btn-danger fileinput-button" id="file" type="file" name="files[]"   multiple   >-->
            <input type="file" class="btn btn-danger fileinput-button kfile" name="vasplus_multiple_files[]" id="vasplus_multiple_files" multiple="multiple" style="padding:5px;"/>

            <table class="table table-striped table-bordered" style="width:60%; border: none;" id="add_files">

                <tbody>

                </tbody>
            </table>
        </div>




                    </div>
                    <div class="modal-footer">
                        <button onclick="ferme();"type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button onclick=" resetForm(this.form);" id="SendBtn" type="submit"  name="myButton" class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
                    </div>
                </div>
            </div>
			    </form>
   </div>
   </div>



<?php
if(isset($_GET['openmodal']) )
{
  if($_GET['openmodal'] == 1){ ?>
        <script type="text/javascript">

                  $(document).ready(function(){
                     $('#sendmail').modal({show:true});

                 });
        </script>
<?php         
    }}
?>




  <!-- modal annuler attente de réponse -->
<div class="modal fade" id="annulerAttenteReponse" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><b>Annuler l'attente de réponse</b></h4>
        </div>
        <div class="modal-body">
          <p>
            
            @if(count($actionsReouRap)!=0)
            <h5> <b>Veuillez sélectionner une action avant de cliquer le bouton "Annuler Attente Réponse"</b></h5>
            <br>
            <table id="tabkkk">
                <tr> <th></th> <th> Action  </th> <th> Mission  </th><th> Dossier   </th> </tr>


                   @foreach ( $actionsReouRap as $rr)
                    <tr> <td style="color: white; font-size: 0px;">{{$rr->id}}</td> <td id="ac{{$rr->id}}">{{$rr->titre}}</td> <td id="mi{{$rr->mission_id}}" >{{ $rr->Mission->typeMission->nom_type_Mission}}</td> <td id="do{{$rr->Mission->dossier_id}}">{{$rr->Mission->dossier->reference_medic}} - {{$rr->Mission->dossier->subscriber_name }} {{$rr->Mission->dossier->subscriber_lastname}}</td>  </tr>
                   
                  @endforeach

              
            </table>
              @else

                 <div> les attentes de réponse pour ce dossier n'existent pas </div>

            @endif
                        
            <!--<input type="button" id="tst" value="OK" onclick="fnselect()" />-->


          </p>
        </div>
        <div class="modal-footer">
          @if(count($actionsReouRap)!=0) <button type="button" id="tst" class="btn btn-default" data-dismiss="modal">Annuler Attente Réponse </button>  @endif
          <button type="button" class="btn btn-default" data-dismiss="modal">Quitter</button>
        </div>
      </div>
  


</div>
      </div>
    <?php }?>

       <script type="text/javascript">
	    
 $('#libre').select2({
                filter: true,
                language: {
                    noResults: function () {
                        return 'Pas de résultats';
                    }
                }
            });
    function checkForm(form) // Submit button clicked
    {

        form.myButton.disabled = true;
        form.myButton.value = "Please wait...";
        return true;
    }

    function resetForm(form) // Reset button clicked
    {
        form.myButton.disabled = false;
        form.myButton.value = "Submit";
    }

    function visibilite(divId)
    {
        //divPrecedent.style.display='none';
        divPrecedent=document.getElementById(divId);
        if(divPrecedent.style.display==='none')
        {divPrecedent.style.display='block';	 }
        else
        {divPrecedent.style.display='none';     }
    }

    $(document).ready(function(){

        $('#theform').submit(function(){
            $(this).children('input[type=submit]').prop('disabled', true);
        });




        fileInputk = document.querySelector('#vasplus_multiple_files');
        fileInputk.addEventListener('change', function(event) {
            var inputk = event.target;

            if (inputk.files.length != khaled.length && inputk.files.length==0 )
            {
                // alert ('ddd');
                fileInputk.value="";
                fileInputk.files= new FileListItem(khaled);

            }
        });

        // ajax save as draft
        $('#description').change(function(){
            var destinataire = $('#destinataire').val();
            var cc = $('#cc').val();
            var cci = $('#cci').val();
            var sujet = $('#sujet').val();
            var contenu = $('#contenu').val();
            var description = $('#description').val();
            var brsaved = $('#brsaved').val();

            if ( (brsaved==0) )
            { //alert('create br');
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('envoyes.savingbr') }}",
                    method:"POST",
                    data:{description:description,destinataire:destinataire,sujet:sujet,contenu:contenu,cc:cc,cci:cci, _token:_token},
                    success:function(data){
                        //   alert('Brouillon enregistré ');

                        document.getElementById('envoye').value=data;
                        document.getElementById('brsaved').value=1;
                        document.getElementById('SendBtn').disabled=false;
                    }
                });
            }else{

                if ( description!='' )
                {             var envoye = $('#envoye').val();

                    //  alert('update br');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('envoyes.updatingbr') }}",
                        method:"POST",
                        data:{envoye:envoye,description:description,destinataire:destinataire,contenu:contenu,cc:cc,cci:cci, _token:_token},
                        success:function(data){
                            //     alert('Brouillon enregistré ');

                            document.getElementById('envoye').value=data;
                            document.getElementById('brsaved').value=1;
                            document.getElementById('SendBtn').disabled=false;


                        }
                    });

                }

            }
        });



        // ajax save as draft
        $('#sujet').change(function(){
            var destinataire = $('#destinataire').val();
            var cc = $('#cc').val();
            var cci = $('#cci').val();
            var sujet = $('#sujet').val();
            var contenu = $('#contenu').val();
            var description = $('#description').val();
            var brsaved = $('#brsaved').val();

            if ( (brsaved==0) )
            { //alert('create br');
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('envoyes.savingbr') }}",
                    method:"POST",
                    data:{description:description,destinataire:destinataire,sujet:sujet,contenu:contenu,cc:cc,cci:cci, _token:_token},
                    success:function(data){
                        //   alert('Brouillon enregistré ');

                        document.getElementById('envoye').value=data;
                        document.getElementById('brsaved').value=1;
                        document.getElementById('SendBtn').disabled=false;

                    }
                });
            }else{

                if ( description!='' )
                {             var envoye = $('#envoye').val();

                    //  alert('update br');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('envoyes.updatingbr') }}",
                        method:"POST",
                        data:{envoye:envoye,description:description,destinataire:destinataire,contenu:contenu,cc:cc,cci:cci, _token:_token},
                        success:function(data){
                            alert('Brouillon enregistré ');

                            document.getElementById('envoye').value=data;
                            document.getElementById('brsaved').value=1;
                            document.getElementById('SendBtn').disabled=false;

                        }
                    });

                }

            }
        });


        objTextBox = document.getElementById("contenu");
        oldValue = objTextBox.value;
        var somethingChanged = false;

        function track_change()
        {
            if(objTextBox.value != oldValue)
            {
                oldValue = objTextBox.value;
                somethingChanged = true;
                changeeditor();
            };

            setTimeout(function() { track_change()}, 5000);

        }


        // setTimeout(function() { track_change()}, 15000);
        track_change();
        function setcontenu(myValue) {
            $('#contenu').val(myValue)
                .trigger('change');
        }


        function changeeditor()
        {    var destinataire = $('#destinataire').val();
            var cc = $('#cc').val();
            var cci = $('#cci').val();
            var sujet = $('#sujet').val();
            var contenu = $('#contenu').val();
            var description = $('#description').val();
            var brsaved = $('#brsaved').val();

            if ((brsaved == 0)) {
                //  alert('content changed');
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('envoyes.savingbr') }}",
                    method: "POST",
                    data: {
                        description: description,
                        destinataire: destinataire,
                        sujet: sujet,
                        contenu: contenu,
                        cc: cc,
                        cci: cci,
                        _token: _token
                    },
                    success: function (data) {
                        //       alert('Brouillon enregistré ');

                        document.getElementById('envoye').value = data;
                        document.getElementById('brsaved').value = 1;
                        document.getElementById('SendBtn').disabled=false;

                    }
                });
            } else {

                //if ( description!='' )
                //{
                var envoye = $('#envoye').val();
                // alert('updating br');
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('envoyes.updatingbr') }}",
                    method: "POST",
                    data: {
                        description: description,
                        envoye: envoye,
                        destinataire: destinataire,
                        contenu: contenu,
                        cc: cc,
                        cci: cci,
                        _token: _token
                    },
                    success: function (data) {
                        //     alert('Brouillon enregistré ');
                        document.getElementById('envoye').value = data;
                        document.getElementById('brsaved').value = 1;
                        document.getElementById('SendBtn').disabled=false;

                    }
                });

                //  }

            }

        }


        $('#attachs').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }
        });

        $('#cc').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }
        });

        $('#cci').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }
        });

    });
 
	
	
      $("#tabkkk tr").click(function(){
         $(this).addClass('selected').siblings().removeClass('selected');    
         var value=$(this).find('td:first').html();
        //alert(value);    
      });

      $('#tst').on('click', function(e){
          var arrid=-1;
          arrid =$("#tabkkk tr.selected td:first").html();
         idac=$("#tabkkk tr.selected td:nth-child(2)").attr('id');
          idmi=$("#tabkkk tr.selected td:nth-child(3)").attr('id');
           iddo=$("#tabkkk tr.selected td:nth-child(4)").attr('id');
           idac=idac.substring(2);
           idmi=idmi.substring(2);
            iddo=iddo.substring(2);
        //alert(idac+" "+idmi+" "+iddo);
           $.ajax({
       
       url : '{{ url('/') }}'+'/annulerAttenteReponseAction/'+arrid,
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {

           alert(data);

           if(String(data).indexOf("Erreur")== -1)
           {

            var rr = confirm("Voulez-vous rester dans la même page ou ouvrir la page de l'action activée. Si vous voulez rester dans la même page cliquez le bouton annuler");
              if (rr == true) {
              location.href = '{{ url('/') }}'+'/dossier/Mission/TraitementAction/'+iddo+'/'+idmi+'/'+idac;  
               } 
           
           //location.reload();
           
           }

            
           }
       }
    });
   
  

      });


      </script>


<!-- Modal -->
<div class="modal fade" id="affectfolder"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="text-align:center" class="modal-title" id="exampleModalLabel">Re-Dispatcher</h3>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <form method="post" >
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="type">Dossier :</label>
                         <select id ="affdoss"  class="form-control select2" style="width: 100%">
                             <option></option>
                         <?php foreach($dossiers as $ds)

                               {
                               echo '<option  title="'.$ds->id.'" value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name.' - '.$ds->subscriber_lastname.' </option>';}     ?>
                         </select>
                            <br><br><br>
                        </div>


                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="updatefolder" onclick="document.getElementById('updatefolder').disabled=true" class="btn btn-primary">Dispatcher</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ URL::asset('resources/assets/js/spectrum.js') }}"></script>
<script src="{{ URL::asset('resources/assets/js/jquery.marker.js') }}"></script>

<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/spectrum.css') }}">
<?php
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
?>
<script>

    $("#affdoss").select2();

    $( document ).ready(function() {



        $('#sending').click(function(){
            var entree = $('#entreeid').val();
            var message = $('#message').html();


                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('emails.accuse') }}",
                    method:"POST",
                    data:{entree:entree,message:message, _token:_token},
                    success:function(data){

                        alert('Envoyé !');


                    }
                });

        });

        $('#updatefolder').click(function(){
            var entree = $('#entreeid').val();
            var dossier = $('#affdoss').val();
             var iddossier = $('#affdoss').find("option:selected").attr("title");


            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('entrees.dispatchf2') }}",
                method:"POST",
                data:{entree:entree,dossier:dossier,iddossier:iddossier, _token:_token},
                success:function(data){

                    window.location =data;



                }
            });

        });



        /****** Hilight Text on mail content ********/

        var target = $('#myTabContent');

        target.marker({
            //overlap:true,
            data : function(e, data) {
               // console.log(JSON.stringify(data))
            },
            debug : function(e, data) {
                    //console.log(JSON.stringify(data))
            }
        });

         //  var data= target.marker(data);


    });

    $('#data').on('click',   function() {

        target.marker('data');


    });

    $('#hiding').on('click',   function() {

        var   div=document.getElementById('emailhead');
        var   div2=document.getElementById('emailbuttons');
        if(div.style.display==='none')
        {
            div.style.display='block';
            div2.style.display='block';
        }
        else
        {
            div.style.display='none';
            div2.style.display='none';
        }

    });

    $('#checkaccuse').on('click',   function() {

        var   div=document.getElementById('formaccuse');
         if(div.style.display==='none')
        {
            div.style.display='block';
         }
        else
        {
            div.style.display='none';
         }

    });




</script>

<style>
  #message {

border: 1px solid #ccc;
padding: 5px;
}
    </style>

<script>

    function checkComment()
    {
        if (document.getElementById('commentuser').value == '') {
         //   alert('Ajouter un commentaire avant de marquer comme traité !');
               $('#actiontabs a[href="#infostab"]').trigger('click');
                $('#btn-cmttag').trigger('click');
                $('#editbtn').trigger('click');
            $("#commentuser").css("border", "2px solid red ");



        }else{
           location.href="{{action('EntreesController@traiter', $entree['id'])}}";
        }
    }
 
function accuse()
    {
var queryParams = new URLSearchParams(window.location.search);

if(queryParams.has('openmodal'))
{queryParams.set("openmodal", '1');
history.replaceState(null, null, "?"+queryParams.toString());
window.location = window.location.href ;}
else
{  window.location = window.location.href + "?openmodal=1";}
    }
function ferme()
    {


     var queryParams = new URLSearchParams(window.location.search);
//alert(queryParams);
queryParams.set("openmodal", '0');
history.replaceState(null, null, "?"+queryParams.toString());
    }

</script>
 

@endsection
