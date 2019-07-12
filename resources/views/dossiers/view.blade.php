@extends('layouts.mainlayout')
<?php 
use App\User ; 
use App\Template_doc ; 
use App\Document ; 
use App\Client;
use App\ClientGroupe;

?>
<?php use \App\Http\Controllers\PrestationsController;
     use  \App\Http\Controllers\PrestatairesController;
?>

<link rel="stylesheet" href="{{ asset('public/css/timelinestyle.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('public/css/timeline.css') }}" type="text/css">
<!--select css-->
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@section('content')

<div class="row">

 @if(session()->has('AffectDossier'))
    <div class="alert alert-success">
       <center> <h4>{{ session()->get('AffectDossier') }}</h4></center>
    </div>
  @endif

     <div class="col-md-6">
        <?php if ((isset($dossier->affecte)) && (!empty($dossier->affecte))) { ?>
        <b>Affecté à:</b> 
        <?php 
        $agentname = User::where('id',$dossier->affecte)->first();
        if ((Gate::check('isAdmin') || Gate::check('isSupervisor')) && !empty ($agentname))
            { echo '<a href="#" data-toggle="modal" data-target="#attrmodal">';}
        echo $agentname['name']; 
        if(Gate::check('isAdmin') || Gate::check('isSupervisor'))
            { echo '</a>';}

        ?>
        <?php }
        else
        {
            if ((Gate::check('isAdmin') || Gate::check('isSupervisor')))
            {echo '<a href="#" data-toggle="modal" data-target="#attrmodal">Non affecté</a>';}
            else
            {echo '<b>Non affecté</b>';} 
        } ?>
    </div>
    <div class="col-md-6" style="text-align: right;padding-right: 35px">
        <div class="page-toolbar">

        <div class="btn-group">
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope"></i> Email <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'client','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au client </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'prestataire','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au Prestataire </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'assure','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            A l'assuré </a>
                    </li>

                </ul>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-default" id="sms">
                    <a style="color:black" href="{{action('EmailController@sms',$dossier->id)}}"> <i class="fas fa-sms"></i> SMS</a>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" id="newfax" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-fax"></i> Fax <i class="fa fa-angle-down"></i>

                </button>


                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="{{route('emails.envoifax',['id'=>$dossier->id,'type'=> 'client','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au client </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoifax',['id'=>$dossier->id,'type'=> 'prestataire','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au Prestataire </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoifax',['id'=>$dossier->id,'type'=> 'libre','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Libre </a>
                    </li>

                </ul>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-default" id="newcalldossier">
                    <i class="fa fa-phone"></i>
                    Tél

                </button>
            </div>
        </div>
    </div>
    </div>


</div>
    <section class="content form_layouts">


        <div class="container-fluid">
<br>
            <div class="row" style="margin-top:10px">
                <div class="col-lg-12">
                    <ul class="nav  nav-tabs">
 
                        <li class="nav-item active">
                            <a class="nav-link  active show" href="#tab2" data-toggle="tab">
                               <i class="fas a-lg fa-exchange-alt"></i>  Echanges
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab3" data-toggle="tab">
                                <i class="fas fa-lg  fa-ambulance"></i>  Prestations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab4" data-toggle="tab">
                                <i class="fas  fa-lg fa-file-archive"></i>  Attachements
                            </a>
                        </li>
                       <!-- <li class="nav-item">
                            <a class="nav-link" href="#tab5" data-toggle="tab">
                                <i class="fas  fa-lg fa-cog"></i>  Autres
                            </a>
                        </li>-->
                        <li class="nav-item">
                            <a class="nav-link" href="#tab6" data-toggle="tab">
                                <i class="fas fa-lg fa-file-word"></i>  Docs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab7" data-toggle="tab">
                                <i class="fas fa-file-import"></i>  OM
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"   href="{{action('DossiersController@fiche',$dossier->id)}}"  >
                                <i class="fas fa-lg fa-cog"></i><B> Gestion</B>
                            </a>
                        </li>


                    </ul>

                </div>
            </div>
            <div class="tab-content mar-top">
			
            <div id="tab2" class="tab-pane fade active in">


                        <section id="timeline">



                            <?php //echo 'Envoyes '.json_encode($envoyes) ?>

                            @if($communins)
                            @foreach($communins as $communin)

                            <article>
                                <div class="inner  <?php if ($communin['boite']==1){echo 'sent ';}?>">
                                <span class="date">

                                 <?php if($communin['type']=="email") {?>

                                    <img  src="{{ asset('public/img/mail.png') }}"  width="60" height="60">

                                    <?php }?><?php if($communin['type']=="sms") {?>
                                    <img  src="{{ asset('public/img/sms.png') }}"  width="60" height="60">
                                    <?php }?>
                                    <?php if($communin['type']=="whatsapp") {?>
                                    <img  src="{{ asset('public/img/whatsapp.png') }}"  width="60" height="60">

                                    <?php }?>
                                    <?php if($communin['type']=="tel") {?>
                                    <img  src="{{ asset('public/img/phone.png') }}"  width="60" height="60">

                                    <?php }?>

                                    <?php if($communin['type']=="fax") {?>
                                    <img  src="{{ asset('public/img/fax.png') }}"  width="60" height="60">
                                    <?php }?>

                                    <?php if($communin['type']=="rendu") {?>
                                    <img  src="{{ asset('public/img/rendu.png') }}"  width="60" height="60">
                                    <?php }?>
                                </span>
                                    <h2 style="font-size: 16px"><?php echo $communin['sujet']; ?></h2>
                                    <p class="overme">

                                        <?php if ($communin['boite']==0)
                                        {  echo '<span class="commsujet" style="font-size:12px"><B>Emetteur: </B>'. $communin['emetteur'].'</span>';
                                        }
                                        ?>

                                        <?php if ($communin['commentaire']!=null)
                                        {  echo '<span style="font-size:12px"><B>Commentaire: </B>'. $communin['commentaire'].'</span>';
                                        }
                                        ?>


                                        <span class="cd-date">

                                            <?php echo /*date('d/m/Y H:i', (*/$communin['reception']/*))*/ ; ?> <i class="fa fa-fw fa-clock-o"></i><br>


                                        </span>
                                        <?php if($communin['type']=="email") {
                                            if($communin['nb_attach']>0) { ?> <span style="font-size:12px"><i class="fa fa-fw fa-paperclip"></i>(<?php echo $communin['nb_attach'];?>) Attachements</span><br>
                                        <?php } } ?>

                                        <?php
                                        if ($communin['boite']==1)
                                        {
                                        ?>
                                        <a class="btn btn-md btn-default" style="margin-top: 10px;" href="{{action('EnvoyesController@view', $communin['id'])}}"> Voir les détails</a><br>

                                        <?php
                                        }
                                        else { ?>
                                        <a class="btn btn-md btn-default" style="margin-top: 10px;" href="{{action('EntreesController@show', $communin['id'])}}"> Voir les détails</a><br>

                                        <?php } ?>

                                    </p>
                                </div>
                            </article>

                            @endforeach
                            @endif

                        </section>





            </div>

            <div id="tab3" class="tab-pane fade">
                <span style="background-color:#fcdcd5;color:black;font-weight:bold">Prestation non effectuée </span> <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addpres" class="btn btn-md btn-success"   data-toggle="modal" data-target="#create"><b><i class="fas fa-plus"></i> Ajouter une Prestation</b></button>

                <table class="table table-striped" id="mytable" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable">
                        <th style="width:10%">Numéro</th>
                        <th style="width:20%">Prestataire</th>
                        <th style="width:20%">Type</th>
                        <th style="width:20%">Spécialité</th>
                        <th style="width:20%">Gouvernorat</th>
                        <th style="width:10%">Prix</th>
                    </tr>

                    </thead>
                    <tbody>

                    @foreach($prestations as $prestation)
                        <?php $dossid= $prestation['dossier_id'];?>
                        <?php $effectue= $prestation['effectue'];
                        if($effectue ==0){$style='background-color:#fcdcd5;';}else{$style='';}
                        ?>

                        <tr  >
                            <td style="width:35%; <?php echo $style;?> ">
                                <a href="{{action('PrestationsController@view', $prestation['id'])}}" >
                                    <?php  echo $prestation['id']  ; ?>
                                </a></td>
                            <td style="width:25%">
                                <?php $prest= $prestation['prestataire_id'];
                                echo PrestationsController::PrestataireById($prest);  ?>
                            </td>
                            <td style="width:20%;">
                                <?php $typeprest= $prestation['type_prestations_id'];
                                echo PrestationsController::TypePrestationById($typeprest);  ?>
                            </td>
                            <td style="width:20%;">
                                <?php $specialite= $prestation['specialite'];
                                echo PrestationsController::SpecialiteById($specialite);  ?>
                            </td>
                            <td style="width:20%;">
                                <?php $gouvernorat= $prestation['gouvernorat'];
                                echo PrestationsController::GouvById($gouvernorat);  ?>
                            </td>
                            <td style="width:20%">{{$prestation->price}}</td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

            <div id="tab4" class="tab-pane fade">

                <table class="table table-striped" id="mytable" style=" ;margin-top:15px;">
                    <thead>
                    <tr id="headtable">
                        <th style="width:15%">Date</th>
                        <th style="width:30%">Titre</th>
                        <th style="width:40%">Description</th>

                        <th style="width:5%">type</th>
                        <th style="width:10%">Boite</th>
                    </tr>

                    </thead>
                    <tbody>
                      @foreach($attachements as $attach)
                      <?php if ($attach->boite < 2) {
                        ?>
                        <tr>
                            <td style="width:15%;"><small><?php echo $attach->created_at;?></small></td>
                            <td  class="overme" style="width:30%;"><small><?php /* if ($attach->dossier!=null) {echo 'Fichier externe';}else{*/ echo $attach->nom; /*}*/ ?></small></td>
                            <td class="overme" style="width:40%;"><small><?php  echo $attach->description;   ?></small></td>

                            <td style="width:5%;"><small><?php
                                    $type= $attach->type;
                                    switch ($type) {
                                        case 'pdf':
                                        echo '<i class="far fa-2X fa-file-pdf"></i>';
                                            break;
                                        case 'txt':
                                            echo '<i class="far fa-2X fa-file-alt"></i>';
                                            break;
                                        case 'png':
                                            echo '<i class="far fa-2X fa-file-image"></i>';
                                            break;
                                        case 'jpg':
                                            echo '<i class="far fa-2X fa-file-image"></i>';
                                            break;
                                        case 'doc':
                                            echo '<i class="far fa-2X  fa-file-word"></i>';
                                            break;
                                        case 'docx':
                                        echo '<i class="far fa-2X fa-file-word"></i>';
                                        break;
                                        case 'xls':
                                            echo '<i class="far fa-2X fa-file-excel"></i>';
                                            break;
                                        case 'xls':
                                            echo '<i class="far fa-2X fa-file-excel"></i>';
                                            break;
                                        default:
                                            echo '<i class="far fa-2X  fa-file"></i>';
                                    }


                                    ?></small></td>
                            <td style="width:10%"><small><?php if ($attach->boite>0) {echo ' Envoi<i class="fas a-lg fa-level-up-alt" />';}else{echo 'Réception<i class="fas a-lg fa-level-down-alt"/>';}?></small></td>

                        </tr>
                        <?php } ?>
                    @endforeach

                    </tbody>
                </table>

            </div>

      <!--      <div id="tab5" class="tab-pane fade">

<br><br>

            </div>-->

            <div id="tab6" class="tab-pane fade">
                <div style="">
                    <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="adddoc" class="btn btn-md btn-success"   data-toggle="modal" data-target="#generatedoc"><b><i class="fas fa-plus"></i> Générer un document</b></button>


                </div>
                <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable">
                        <th style="">Document</th>
                        <!--<th style="">Description</th>-->
                        <th style="">Historique</th>
                        <th style="">Actions</th>
                     </tr>

                    </thead>
                    <tbody>
                    @foreach($documents as $doc)
                        <tr>
                            <td style=";"><?php echo $doc->titre; ?></td>
                            <!--<td style=";"><?php //echo $doc->description; ?></td>-->
                            <td style=";">
                            <?php
                                if ($doc->parent !== null)
                                {
                                    echo '<button type="button" class="btn btn-primary panelciel" style="color:black;background-color: rgb(214,239,247) !important;" id="btnhisto" onclick="historiquedoc('.$doc->parent.');"><i class="far fa-eye"></i> Voir</button>';
                                   
                                }
                                else
                                {
                                    echo "Aucun";
                                }
                            ?>
                            </td>
                            <?php 
                            $pathdoc = storage_path().$doc->emplacement;
                            $templatedoc = $doc->template;
                            ?>
                            <td>
                                    <div class="page-toolbar">

                                    <div class="btn-group">
                                        <?php
                                            if (stristr($doc->emplacement,'annulation')=== FALSE) 
                                            {
                                        ?>
                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,227,214) !important;" id="btnannremp">
                                                <a style="color:black" href="#" id="annremp" onclick="remplacedoc(<?php echo $doc->id; ?>,<?php echo $templatedoc; ?>);"> <i class="far fa-plus-square"></i> Annuler et remplacer</a>
                                            </button>
                                        </div>

                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,214,214) !important;" id="btnann">
                                                <a style="color:black"  onclick="annuledoc('<?php echo $doc->titre; ?>',<?php echo $doc->id; ?>,<?php echo $templatedoc; ?>);" href="#" > <i class="far fa-window-close"></i> Annuler</a>
                                            </button>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(214,247,218) !important;" id="btntele">
                                                <a style="color:black" onclick='modalodoc("<?php echo $doc->titre; ?>","{{ URL::asset('storage'.'/app/'.$doc->emplacement) }}");' ><i class="fas fa-external-link-alt"></i> Aperçu</a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
<!-- Modal Ouvrir Document-->
<div class="modal fade" id="opendoc" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:900px;height: 450px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="doctitle"></h5>
            </div>
            <div class="modal-body">
                <div class="card-body">

                    <iframe id="dociframe" src="" frameborder="0" style="width:100%;min-height:640px;"></iframe>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
            <div id="tab7" class="tab-pane fade">
                <div style="">
                    <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addom" class="btn btn-md btn-success"   data-toggle="modal" data-target="#generateom"><b><i class="fas fa-plus"></i> Créer un ordre de mission</b></button>


                </div>
                <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable">
                        <th style="">OM</th>
                        <!--<th style="">Description</th>-->
                        <th style="">Historique</th>
                        <th style="">Actions</th>
                     </tr>

                    </thead>
                    <tbody>
                        <tr>
                            <td style=";">OM_TAXI</td>
                            <td style=";">
                            </td>
                            <td>
                                    <div class="page-toolbar">

                                    <div class="btn-group">
                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,227,214) !important;" id="btnannremp">
                                                <a style="color:black" href="#" id="annremp" onclick="remplacedoc();"> <i class="far fa-plus-square"></i> Annuler et remplacer</a>
                                            </button>
                                        </div>

                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,214,214) !important;" id="btnann">
                                                <a style="color:black"  onclick="annuledoc();" href="#" > <i class="far fa-window-close"></i> Annuler</a>
                                            </button>
                                        </div>
                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(214,247,218) !important;" id="btntele">
                                                <a style="color:black" href="" ><i class="fa fa-download"></i> Télécharger</a>
                                            </button>
                                        </div>
                                    </div>
                                    </div>
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>

            </div>




        </div>
    </section>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<?php use \App\Http\Controllers\UsersController;
$users=UsersController::ListeUsers();

$CurrentUser = auth()->user();

$iduser=$CurrentUser->id;

?>




<!-- Modal Email
<div class="modal fade" id="createemail" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Ajouter une adresse Email </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">

                        <form id="addemailform" novalidate="novalidate">
                            {{-- csrf_field() --}}

                            <input id="parent" name="parent" type="hidden" value="{{-- $dossier->id --}}">
                            <div class="form-group " >
                                <label for="emaildoss">Email</label>
                                <div class=" row  ">
                                    <input class="form-control" type="email" required id="emaildoss"/>

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="DescrEmail">nom</label>
                                <div class="row">
                                    <input type="text" class="form-control"  id="DescrEmail" />

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="DescrEmail">qualité</label>
                                <div class="row">
                                    <input type="text" class="form-control"  id="qualite" />

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="DescrEmail">Tel</label>
                                <div class="row">
                                    <input type="text" class="form-control"  id="telmail" />

                                </div>
                            </div>
                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="emailadd" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </div>
</div>
-->
<!-- Modal Document-->
<div class="modal fade" id="generatedoc" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Choisir la template du document </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        {{ csrf_field() }}

                        <form id="gendocform" novalidate="novalidate">

                            <input id="dossier" name="dossier" type="hidden" value="{{ $dossier->id}}">
                            <div class="form-group " >
                                <label for="emaildoss">Template</label>
                                <div class=" row  ">
                                    <select class="form-control select2" style="width: 230px" required id="templatedoc" name="templatedoc" >
                                        <option value="Select">Selectionner</option>
                                    <?php
                                        $usedtemplates = Document::where('dossier',$dossier->id)->distinct()->get(['template']);
                                        $usedtid=array();
                                        foreach ($usedtemplates as $tempu) {
                                            $usedtid[]=$tempu['template'];
                                        }
                                        $templatesd = Template_doc::get();
                                        $docwithcl = array();
                                    ?>
                                        @foreach ($templatesd as $tempdoc)
                                           @if (! in_array($tempdoc["id"],$usedtid))
                                                <option value={{ $tempdoc["id"] }} >{{ $tempdoc["nom"] }}</option>
                                            @endif                                            
                                        @endforeach
                                        
                                   </select>
                                </div>
                            </div>

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="gendoc" class="btn btn-primary">Choisir</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal OM-->
<div class="modal fade" id="generateom" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Créer un ordre de mission </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        {{ csrf_field() }}

                        <form id="genomform" novalidate="novalidate">

                            <input id="dossom" name="dossom" type="hidden" value="{{ $dossier->id}}">
                            <div class="form-group " >
              
                                  <div class=" row  ">
                                    <div class="col-md-3"><label for="templateom">Ordre de mission</label></div>
                                      <select class="form-control select2" style="width: 230px" required id="templateom" name="templateom" >
                                          <option value="Select">Selectionner</option>
                                          <option value="Taxi">Taxi</option>
                                      <?php
                                         /* $usedtemplates = Document::where('dossier',$dossier->id)->distinct()->get(['template']);
                                          $usedtid=array();
                                          foreach ($usedtemplates as $tempu) {
                                              $usedtid[]=$tempu['template'];
                                          }
                                          $templatesd = Template_doc::get();
                                          $docwithcl = array();*/
                                      ?>
                                          {{--
                                          @foreach ($templatesd as $tempdoc)
                                             @if (! in_array($tempdoc["id"],$usedtid))
                                                  <option value={{ $tempdoc["id"] }} >{{ $tempdoc["nom"] }}</option>
                                              @endif                                            
                                          @endforeach


                                          nom group client
nom client
ref client dossier

dossier:
customer_id       ---->    clients: name / groupe    ---->    label
reference_customer


                                          --}}
                                          
                                     </select>
                                  </div>
                                  <div class=" row  " style="margin-top: 15px">
                                    <div class="col-md-3"><label for="emispar">Émis par</label></div>
                                      <select class="form-control" style="width: 230px" required id="emispar" name="emispar" >
                                          <option value="Select">Selectionner</option>
                                          <option value="najda">Najda Assistance </option>
                                          <option value="medicm">Medic Multiservices </option>
                                          <option value="medict">Medic transport </option>
                                          <option value="vat">VAT transport </option>
                                          <option value="medici">Medic International </option>
                                     </select>
                                  </div>
                                  <div class=" row  " style="margin-top: 15px">
                                    <div class="col-md-3"><label for="affectea">Affecté à</label></div>
                                      <select class="form-control" style="width: 230px" required id="affectea" name="affectea" >
                                          <option value="Select">Selectionner</option>
                                          <option value="interne">Société soeur</option>
                                          <option value="externe">Prestataire externe</option>
                                     </select>
                                  </div>
                              </div>

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="genom" class="btn btn-primary">Valider</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal template html doc-->
<div class="modal fade" id="templatehtmldoc" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:900px;height: 450px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Veuillez éditer les champs du document</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        

                        <form id="gendocfromhtml" novalidate="novalidate" method="post" action="{{ route('documents.adddocument') }}">
                            {{ csrf_field() }}
                            <input id="dossdoc" name="dossdoc" type="hidden" value="{{ $dossier->id}}">
                            <input type="hidden" name="templatedocument" id="templatedocument" >
                            <input type="hidden" name="iddocparent" id="iddocparent" >
                            <iframe src="#" id="templatefilled" name="templatefilled" style="width:100%;height:100%">content</iframe>

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="gendochtml" class="btn btn-primary">Générer</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal template html om-->
<div class="modal fade" id="templatehtmlom" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:900px;height: 450px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Veuillez éditer les champs de l'ordre de mission</h5>


            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        

                        <form id="genomfromhtml" novalidate="novalidate" method="post" action="">
                            {{ csrf_field() }}
                            <input id="dossdoc" name="dossdoc" type="hidden" value="{{ $dossier->id}}">
                            <input type="hidden" name="templateordrem" id="templateordrem" >
                            <input type="hidden" name="idomparent" id="idomparent" >
                            <iframe src="#" id="omfilled" name="omfilled" style="width:100%;height:100%">content</iframe>

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="genomhtml" class="btn btn-primary">Générer</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal historique doc-->
<div class="modal fade" id="modalhistodoc" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModal2">Historique du document</h4>

            </div>
            <div class="modal-body">
                <div class="card-body">
                    <h5 style="font-size: 20px; font-weight: 900; color: slategrey;" id="dochistoname"></h5>
                    <table class="table table-striped" id="tabledocshisto" style="width:100%;margin-top:15px;">
                            <thead>
                            <tr id="headtable">
                                <th style="">Date de génération</th>
                                <th style="">Actions</th>
                             </tr>

                            </thead>
                            <tbody>
                            </tbody>
                    </table>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<?php if ((Gate::check('isAdmin') || Gate::check('isSupervisor'))) { ?>
<!-- Modal attribution dossier-->
<div class="modal fade" id="attrmodal" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <form  method="post" action="{{ route('affectation.dossier') }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Affectation dossier</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">
                        
                        
                            {{ csrf_field() }}
                            <input id="dossierid" name="dossierid" type="hidden" value="{{ $dossier->id}}">
                            <input id="affecteurdoss" name="affecteurdoss" type="hidden" value="{{ Auth::user()->id}}">
                            <input id="statdoss" name="statdoss" type="hidden" value="existant">

                            <div class="form-group " >
                                <div class=" row  ">
                                    <div class="form-group mar-20">
                                        <label for="agent" class="control-label" style="padding-right: 20px">Agent</label>
                                        <select id="agent" name="agent" class="form-control select2" style="width: 230px">
                                            <option value="Select">Selectionner</option>
                                            <?php $agents = User::get(); ?>
                                           
                                                @foreach ($agents as $agt)
                                                <?php if (!empty ($agentname)) { ?>
                                                @if ($agentname["id"] == $agt["id"])
                                                    <option value={{ $agt["id"] }} selected >{{ $agt["name"] }}</option>
                                                @else
                                                    <option value={{ $agt["id"] }} >{{ $agt["name"] }}</option>
                                                @endif
                                                
                                                <?php }
                                                else
                                                      {  echo '<option value='.$agt["id"] .' >'.$agt["name"].'</option>';}
                                                ?>
                                                @endforeach    
                                        </select>
                                    </div>
                                </div>
                            </div>
                      

                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="submit" id="attribdoss" class="btn btn-primary">Affecter</button>
            </div>
        </div>
          </form>
    </div>
</div>

<!-- Modal Email -->
<div class="modal fade" id="adding7" tabindex="-1" role="dialog" aria-labelledby="exampleModal7" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal7">Ajouter une adresse Email </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">

                        <form   id="fggf" name="">
                            {{ csrf_field() }}

                            <div class="form-group " >
                                <label for="adresse">Nom</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="nome"/>

                                </div>
                            </div>
                            <div class="form-group " >
                                <label for="adresse">Prénom</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="prenome"/>

                                </div>
                            </div>

                            <div class="form-group " >
                                <label for="adresse">Fonction</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="fonctione"/>

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="code">Adresse Email</label>
                                <div class="row">
                                    <input type="email"   class="form-control"  id="emaildoss" />

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="code">Remarque</label>
                                <div class="row">
                                    <textarea   class="form-control"  id="remarquee" ></textarea>

                                </div>
                            </div>

                            <input id="natureem" name="nature" type="hidden" value="emaildoss">


                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <span type="button" id="btnaddemail" class="btn btn-primary">Ajouter</span>
            </div>
        </div>
    </div>
</div>


<!-- Modal Tel -->
<div class="modal fade" id="adding6" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Ajouter une numéro Tel </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">

                        <form   id="fghgf" name="">
                            {{ csrf_field() }}

                            <div class="form-group " >
                                <label for="adresse">Nom</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="nomt"/>

                                </div>
                            </div>
                            <div class="form-group " >
                                <label for="adresse">Prénom</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="prenomt"/>

                                </div>
                            </div>

                            <div class="form-group " >
                                <label for="adresse">Fonction</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="fonctiont"/>

                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="code">Tel</label>
                                <div class="row">
                                    <input type="text"   class="form-control"  id="teldoss" />

                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="code">Remarque</label>
                                <div class="row">
                                    <textarea   class="form-control"  id="remarquet" ></textarea>

                                </div>
                            </div>
                            <input id="naturetel" name="nature" type="hidden" value="teldoss">

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <span type="button" id="btnaddtel" class="btn btn-primary">Ajouter</span>
            </div>
        </div>
    </div>
</div>





<?php } ?>


<!-- Modal -->
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel" style="text-align:center">Ajouter une Nouvelle prestation</h3>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">

                        <form id="addpresform" novalidate="novalidate">
                            {{ csrf_field() }}

                            <input id="idprestation" name="idprestation" type="hidden" value="68356">
                            <div class="form-group " >
                                <label>Type de prestations</label>
                                <div class=" row  ">
                                    <select class="itemName form-control col-lg-12  " style="width:400px" name="itemName"    id="typeprest">
                                        <option></option>
                                        @foreach($typesprestations as $aKey)
                                            <option     value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>

                            <div class="form-group ">
                                <label>Spécialité</label>
                                <div class="row">
                                    <select class="form-control  col-lg-12 " style="width:400px" name="specialite"    id="specialite">
                                        <option></option>
                                        @foreach($specialites as $sp)
                                            <option   value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label>Gouvernorat de couverture</label>
                                <div class="row">
                                    <select class="form-control  col-lg-12 " style="width:400px" name="gouv"    id="gouvcouv">
                                        <option></option>
                                        @foreach($gouvernorats as $aKeyG)
                                            <option   value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>



                            <div class="form-group">
                                 <label class="control-label">Date de prestation <span class="required" aria-required="true"> * </span></label>
                                <input value='<?php echo date('d/m/Y'); ?>' class="form-control datepicker-default" name="pres_date" id="pres_date" data-required="1" required="" aria-required="true">
                            </div>
<!--
                            <div style="align:center;text-align:center">

                                <span style="align:center" id="check" class="btn btn-danger">Chercher des prestataires</span>

                            </div>
-->
                            <div id="data">

                            </div>
                            <link href="http://demo.chandra-admin.com/assets/vendors/Buttons/css/buttons.css" rel="stylesheet">
                            <link href="http://demo.chandra-admin.com/assets/vendors/hover/hover.css" rel="stylesheet">
                            <link href="http://demo.chandra-admin.com/assets/css/custom_css/advbuttons.css" rel="stylesheet">
                            <a href="#" class="hvr-shrink button button-3d button-success button-rounded" style="display:none;margin-top:40px;margin-bottom:30px" id="choisir"><i class="fa fa-check"></i>  Sélectionner</a>
                            <button style="display:none;margin-top:50px;margin-bottom:50px" id="showNext" type="button" class="hvr-wobble-horizontal btn btn-lg btn-labeled btn-info">
                                Suivant
                                <span class="btn-label" style="left: 13px;">
                                                    <i class="fa fa-chevron-right"></i>
                                                </span>
                            </button>

                            <div id="termine" style="display:none;height:120px;align:center;">
                                <center><br>   Fin de la liste.<br></center>

                                <button style="margin:20px 0px 20px 40px" id="essai2" type="button" class="btn btn-labeled btn-default btn-lg hvr-wobble-to-top-right right1">
                                                <span class="btn-label">
                                                    <i class="fa fa-refresh"></i>
                                                </span>
                                    Réessayez
                                </button>
                            </div>
                            <input type="hidden" id="selected" value="0">
                             <input type="hidden" id="par" value="<?php echo $iduser;?>">
                            <label>Prestataire</label>

                            <select style="margin-top:10px;margin-bottom:10px;" disabled id="selectedprest"  class="form-control" value=" ">
                                <option></option>
                            @foreach($prestataires as $prest)
                                <option    value="<?php echo $prest->id;?>"> <?php echo $prest->name;?></option>
                            @endforeach
                            </select>
                            <input id="dossier" name="dossier" type="hidden" value="{{ $dossier->id}}">

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="add2" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>

<script>
function modalodoc(titre,emplacement)
{
    //alert(titre+" | "+emplacement);
    $("#doctitle").text(titre);
    document.getElementById('dociframe').src ="https://view.officeapps.live.com/op/view.aspx?src="+emplacement;
    $("#opendoc").modal('show');
}
function remplacedoc(iddoc,template)
{
    //alert(iddoc+' | '+template);

        var dossier = $('#dossier').val();
        var tempdoc = template;
        $("#gendochtml").prop("disabled",false);
        if ((dossier != '') )
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('documents.htmlfilled') }}",
                method:"POST",
                data:{dossier:dossier,template:tempdoc,parent:iddoc, _token:_token},
                success:function(data){
                        filltemplate(data,tempdoc);
                        // set iddocparent value
                        $('#iddocparent').val(iddoc);
                        //alert(JSON.stringify(data));
                }
            });
        }else{
            // alert('ERROR');
        }
}

function annuledoc(titre,iddoc,template)
{
    //alert(iddoc+' | '+template);

        var dossier = $('#dossier').val();
        var tempdoc = template;
        $("#gendochtml").prop("disabled",false);
        
        //alert(tempdoc);
        var r = confirm("Êtes-vous sûr de vouloir supprimer le document: "+titre+" ? ");
        if (r == true) {
          //alert("You pressed OK!");
          if ((dossier != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('documents.canceldoc') }}",
                    method:"POST",
                    data:{dossier:dossier,template:tempdoc,parent:iddoc, _token:_token},
                success:function(data){
                    //alert(JSON.stringify(data));
                    console.log(data);
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //alert('status code: '+jqXHR.status+' errorThrown: ' + errorThrown + ' jqXHR.responseText: '+jqXHR.responseText);
                    alert('Erreur lors de lannulation du document');
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
                });
            }
        }
}

// affichage de lhistorique du document
    
    function historiquedoc(doc){
        //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        $.ajax({
                url:"{{ route('documents.historique') }}",
                method:"POST",
                //'&_token='+_token
                data:'_token='+_token+'&doc='+doc,
                success:function(data){
                    //alert(JSON.stringify(data));
                    var histdoc = JSON.parse(data);
                    // vider le contenu du table historique
                    $("#tabledocshisto tbody").empty();
                    var items = [];
                    $.each(histdoc, function(i, field){
                      items.push([ i,field ]);
                    });
                    // affichage template dans iframe
                    $.each(items, function(index, val) {

                    //titre du document
                    if (val[0]==0)
                    {
                        $("#dochistoname").text(val[1]['titre']);
                    }

                    //alert(val[0]+" | "+val[1]['emplacement']+" | "+val[1]['updated_at']);
                    urlf="{{ URL::asset('storage'.'/app/') }}";
                    aurlf="<a style='color:black' href='"+urlf+"/"+val[1]['emplacement']+"' ><i class='fa fa-download'></i> Télécharger</a>";
                    $("#tabledocshisto tbody").append("<tr><td>"+val[1]['updated_at']+"</td><td>"+aurlf+"</td></tr>");

                    });

                    $("#modalhistodoc").modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //alert('status code: '+jqXHR.status+' errorThrown: ' + errorThrown + ' jqXHR.responseText: '+jqXHR.responseText);
                    alert('Erreur lors de recuperation de l historique du document');
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
    }

function filltemplate(data,tempdoc)
{
   // window.location =data; hde gendocform and display template filled
   $("#generatedoc").modal('hide');
   //change html template content
   var templateexist = true;
   var parsed = JSON.parse(data);
   var items = [];
   var html_string="";
   $.each(parsed, function(i, field){
      items.push([ i,field ]);
    });
   // affichage template dans iframe
  $.each(items, function(index, val) {

        //recuperer la template html du document
        if(val[0] ==='templatehtml') 
            {
                if ((val[1].includes(undefined)) || (!val[1])) 
                {
                    templateexist = false;
                    alert("la template html du document n'est pas bien défini ");
                }
                else
                {    
                    html_string= "{{asset('public/') }}"+"/"+val[1];

                }
                
            }
        //verifier la templte rtf du document
        if(val[0] ==='templatertf') 
            {
                if ((val[1].includes(undefined)) || (!val[1])) 
                {
                    $("#gendochtml").prop("disabled",true);
                    alert("la template rtf du document n'est pas bien défini ");
                }
                else
                {    
                    $("#templatedocument").val(tempdoc);
                }
                
            }

    });

  if (templateexist)
    {

        // remplissage de la template dans iframe
        var numparam = 0;
        $.each(items, function(index, val) {
            // les champs du document
            if ((val[0] !=='templatertf') && (val[0] !=='templatehtml') /* && (val[0].indexOf("CL_") == -1)*/ )
            {
                if (numparam == 0)
                {
                    html_string=html_string+'?';
                }
                else
                {
                    html_string=html_string+'&';
                }

                html_string=html_string+val[0]+'='+val[1];

                numparam ++;
            }
        });



        //chargement du contenu et affichage du preview du document
        //alert(html_string);
        document.getElementById('templatefilled').src = html_string;
        $("#templatehtmldoc").modal('show');


    }
}

    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var dossier = $('#iddossupdate').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('dossiers.updating') }}",
            method: "POST",
            data: {dossier: dossier , champ:champ ,val:val, _token: _token},
            success: function (data) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });
        // } else {

        // }
    }


    function disabling(elm) {
        var champ=elm;

        var val =0;
        var dossier = $('#iddossupdate').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('dossiers.updating') }}",
            method: "POST",
            data: {dossier: dossier , champ:champ ,val:val, _token: _token},
            success: function (data) {
                if (elm=='franchise'){
                    $('#nonfranchise').animate({
                        opacity: '0.3',
                    });
                    $('#nonfranchise').animate({
                        opacity: '1',
                    });
                }
                if (elm=='is_hospitalized'){
                    $('#nonis_hospitalized').animate({
                        opacity: '0.3',
                    });
                    $('#nonis_hospitalized').animate({
                        opacity: '1',
                    });
                }


            }
        });
        // } else {

        // }
    }



    $(document).ready(function() {
    $("#agent").select2();
    $("#templatedoc").select2();
    $('#emailadd').click(function(){
        var parent = $('#parent').val();
        var champ = $('#emaildoss').val();
        var nom = $('#DescrEmail').val();
        var tel = $('#telmail').val();
        var qualite = $('#qualite').val();
        if ((champ != '') )
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('dossiers.addemail') }}",
                method:"POST",
                data:{parent:parent,champ:champ,nom:nom,tel:tel,qualite:qualite, _token:_token},
                success:function(data){

                    //   alert('Added successfully');
                    window.location =data;

                }
            });
        }else{
            // alert('ERROR');
        }
    });

// fonction du remplissage de la template web du document
    $('#gendoc').click(function(){
        var dossier = $('#dossier').val();
        var tempdoc = $("#templatedoc").val();
        $("#gendochtml").prop("disabled",false);
        // renitialise la val de parentdoc
        $('#iddocparent').attr('value', '');  
        if ((dossier != '') )
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('documents.htmlfilled') }}",
                method:"POST",
                data:{dossier:dossier,template:tempdoc, _token:_token},
                success:function(data){
                     if (data !== 'nogop')
                    {
                        filltemplate(data,tempdoc);
                    }
                    else
                    {
                        alert("OPERATION NON AUTORISE: Le dossier n'a pas un GOP Spécifié!");
                    }
                }
            });
        }else{
            // alert('ERROR');
        }
    });

// fonction du remplissage de la template web du OM
    $('#genom').click(function(){
        var dossier = $('#dossier').val();
        var tempom = $("#templateom").val();
        if (tempom==="Select")
        {
            alert("Veuillez selectionner un ordre de mission");
            return false;
        }
        var emispar = $("#emispar").val();
        if (emispar==="Select")
        {
            alert("Veuillez selectionner l'entitée qui émis l'ordre de mission");
            return false;
        }
        var affectea = $("#affectea").val();
        if (affectea==="Select")
        {
            alert("Veuillez selectionner à qui sera affecté l'ordre de mission");
            return false;
        }
        //$("#gendochtml").prop("disabled",false);
        // renitialise la val de parentdoc
        //$('#iddocparent').attr('value', '');  
        if ((dossier != '') )
        {
            var _token = $('input[name="_token"]').val();
            /*$.ajax({
                url:"{{-- route('documents.htmlfilled') --}}",
                method:"POST",
                data:{dossier:dossier,template:tempom, _token:_token},
                success:function(data){
                        afficheom(data,tempom);
                }
            });*/
            //alert(dossier+" | "+tempom+" | "+emispar+" | "+affectea);
            afficheom(emispar,affectea,tempom,dossier);

        }else{
            // alert('ERROR');
        }
    });

    function afficheom(emispar,affectea,tempom,dossier)
    {
        $("#generateom").modal('hide');
        var url = '<?php echo url('/'); ?>/public/preview_templates/odm_taxi.php?emispar='+emispar+'&affectea='+affectea+'&dossier='+dossier+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';
         document.getElementById("omfilled").src = url;

        
        $("#templatehtmlom").modal('show');
    }

    $('#gendochtml').click(function(){
        //alert($("#templatedocument").val());
        //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        var dossier = $('#dossdoc').val();
        var tempdoc = $("#templatedocument").val();
        var idparent = '';
        // verifier si cest le cas de annule et remplace pour sauvegarder lid du parent
        if ($('#iddocparent').val())
        {
            idparent = $('#iddocparent').val();
            console.log('parent: '+idparent);
        }
        $.ajax({
                url:"{{ route('documents.adddocument') }}",
                method:"POST",
                //'&_token='+_token
                data:$("#templatefilled").contents().find('form').serialize()+'&_token='+_token+'&dossdoc='+dossier+'&templatedocument='+tempdoc+'&parent='+idparent,
                success:function(data){
                    //alert(JSON.stringify(data));
                    console.log(data);
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //alert('status code: '+jqXHR.status+' errorThrown: ' + errorThrown + ' jqXHR.responseText: '+jqXHR.responseText);
                    alert('Erreur lors de la generation du document');
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
    });

    $('#genomhtml').click(function(){
        //alert($("#templatedocument").val());
        //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        var dossier = $('#dossom').val();
        var tempdoc = $("#templateordrem").val();
        var idparent = '';
        // verifier si cest le cas de annule et remplace pour sauvegarder lid du parent
        if ($('#idomparent').val())
        {
            idparent = $('#idomparent').val();
            console.log('parent: '+idparent);
        }
        $.ajax({
                url:"{{ route('ordremissions.export_pdf_odmtaxi') }}",
                method:"POST",
                //'&_token='+_token
                data:$("#omfilled").contents().find('form').serialize()+'&_token='+_token+'&dossdoc='+dossier+'&templatedocument='+tempdoc+'&parent='+idparent,
                success:function(data){
                    //alert(JSON.stringify(data));
                    console.log(data);
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //alert('status code: '+jqXHR.status+' errorThrown: ' + errorThrown + ' jqXHR.responseText: '+jqXHR.responseText);
                    alert('Erreur lors de la generation du document');
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
    });

        $('#btnaddemail').click(function(){
            var parent = $('#iddossupdate').val();
            var nom = $('#nome').val();
            var prenom = $('#prenome').val();
            var fonction = $('#fonctione').val();
             var email = $('#emaildoss').val();
             var observ = $('#remarquee').val();
            var nature = $('#natureem').val();
            if ((email != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.addressadd') }}",
                    method:"POST",
                    data:{parent:parent,nom:nom,prenom:prenom,fonction:fonction,email:email,observ: observ, nature:nature, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                // alert('ERROR');
            }
        });


        $('#btnaddtel').click(function(){
            var parent = $('#iddossupdate').val();
            var nom = $('#nomt').val();
            var prenom = $('#prenomt').val();
            var fonction = $('#fonctiont').val();
            var tel = $('#teldoss').val();
             var observ = $('#remarquet').val();
            var nature = $('#naturetel').val();
            if ((tel != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.addressadd2') }}",
                    method:"POST",
                    data:{parent:parent,nom:nom,prenom:prenom,fonction:fonction,tel:tel,observ: observ, nature:nature, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                  alert('ERROR');
            }
        });

});

</script>

@section('footer_scripts')


@stop

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>

<script>



    $(function () {


        $('#add2').click(function(){
             var prestataire = $('#selectedprest').val();
            var dossier_id = $('#dossier').val();

            var typeprest = $('#typeprest').val();
            var gouvernorat = $('#gouvcouv').val();
            var specialite = $('#specialite').val();
            var date = $('#pres_date').val();

            //   gouvcouv
            if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
               {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('prestations.saving') }}",
                method:"POST",
                data:{date:date,prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat,typeprest:typeprest, _token:_token},
                success:function(data){

                window.location =data;

                },
                error: function(jqXHR, textStatus, errorThrown) {
                //    alert('msg : '.jqXHR.status);
                    alert('msg 2 : '.errorThrown);
                }

            });
              }else{
             alert('ERROR');
             }
        });

        $('.radio1').click(function() {

            var   div=document.getElementById('montantfr');
            if(div.style.display==='none')
            {div.style.display='block';	 }
            else
            {div.style.display='none';     }

            var   div2=document.getElementById('plafondfr');
            if(div2.style.display==='none')
            {div2.style.display='block';	 }
            else
            {div2.style.display='none';     }
        });

        $('#btn01').click(function() {

            var   div=document.getElementById('ben2');
            if(div.style.display==='none')
            {div.style.display='block';	 }
            else
            {div.style.display='none';     }


        });

        $('#btn02').click(function() {

            var   div=document.getElementById('ben3');
            if(div.style.display==='none')
            {div.style.display='block';	 }
            else
            {div.style.display='none';     }


        });


        $('#btn03').click(function() {

            var   div=document.getElementById('adresse2');
            if(div.style.display==='none')
            {div.style.display='block';	 }
            else
            {div.style.display='none';     }

        });


        $('#btn04').click(function() {

            var   div=document.getElementById('adresse3');
            if(div.style.display==='none')
            {div.style.display='block';	 }
            else
            {div.style.display='none';     }


        });

        $("#typeprest").change(function() {

            document.getElementById('termine').style.display = 'none';
            document.getElementById('showNext').style.display='none';
            document.getElementById('choisir').style.display='none';
            document.getElementById('selectedprest').value=0;

        });

        $("#gouvcouv").change(function(){
            //  prest = $(this).val();
            document.getElementById('selectedprest').value=0;

            var  type =document.getElementById('typeprest').value;
            var  gouv =document.getElementById('gouvcouv').value;
            var  specialite =document.getElementById('specialite').value;
            if((type !="")&&(gouv !=""))
            {
                var _token = $('input[name="_token"]').val();

                document.getElementById('termine').style.display = 'none';
                document.getElementById('choisir').style.display = 'none';

                $.ajax({
                    url:"{{ route('dossiers.listepres') }}",
                    method:"post",

                    data:{gouv:gouv,type:type,specialite:specialite, _token:_token},
                    success:function(data){

                        //     alert('1'+data);
                        //   alert('Added successfully');
                        // alert('2'+JSON.parse((data)));
                        $('#data').html(data);
                        //window.location =data;
                        console.log(data);
                        ////       data.map((item, i) => console.log('Index:', i, 'Id:', item.id));
                        var  total =document.getElementById('total').value;

                        if(parseInt(total)>0)
                        {
                            document.getElementById('showNext').style.display='block';
                        }

                    }
                }); // ajax

            }else{
                alert('SVP, Sélectionner le gouvernorat et la spécialité');
            }
        }); // change

        $("#choisir").click(function() {
            //selected= document.getElementById('selected').value;
            selected=    $("#selected").val();
            document.getElementById('selectedprest').value = document.getElementById('prestataire_id_'+selected).value ;


        });


        $("#essai2").click(function() {
            document.getElementById('termine').style.display = 'none';
            document.getElementById('choisir').style.display = 'block';
            document.getElementById('showNext').style.display = 'block';
            document.getElementById('item1').style.display = 'block';
            document.getElementById('selected').value = 1;
            document.getElementById('selectedprest').value = 0;

        });


        $("#showNext").click(function() {
            document.getElementById('selectedprest').value = 0;

            var selected = document.getElementById('selected').value;
            var total = document.getElementById('total').value;
            //     alert(selected);
            //    alert(total);
            var next = parseInt(selected) + 1;
            document.getElementById('selected').value = next;

            if ((selected == 0)) {
                document.getElementById('termine').style.display = 'none';
                document.getElementById('item1').style.display = 'block';
                document.getElementById('choisir').style.display = 'block';

                //document.getElementById('selected').value=1;
                // $("#selected").val('1');

            }

            if ((selected) == (total  )) {//alert("Il n y'a plus de prestataires, Ressayez");
                document.getElementById('termine').style.display = 'block';

                document.getElementById('item'+(selected)).style.display = 'none';
                document.getElementById('showNext').style.display = 'none';
                document.getElementById('choisir').style.display = 'none';


            } else {

                if ((selected != 0) && (selected <= total + 1)) {
                    document.getElementById('choisir').style.display = 'block';
                    document.getElementById('termine').style.display = 'none';
                    document.getElementById('item' + selected).style.display = 'none';
                    document.getElementById('item' + next).style.display = 'block';


                    $("#selected").val(next);



                }
            }

            if(next>parseInt(total)+1) {
                document.getElementById('item' + selected).style.display = 'none';
            }


        });



    }); // $ function



</script>
<style>.headtable{background-color: grey!important;color:white;}
    table{margin-bottom:40px;}
</style>



<style>

    section#timeline {
        width: 80%;
        margin: 20px auto;
        position: relative;
    }
    section#timeline:before {
        content: '';
        display: block;
        position: absolute;
        left: 50%;
        top: 0;
        margin: 0 0 0 -1px;
        width: 2px;
        height: 100%;
        background: rgba(255,255,255,0.2);
    }
    section#timeline article {
        width: 100%;
        margin: 0 0 20px 0;
        position: relative;
    }
    section#timeline article:after {
        content: '';
        display: block;
        clear: both;
    }
    section#timeline article div.inner {
        width: 40%;
        float: left;
        margin: 5px 0 0 0;
        border-radius: 6px;
    }
    section#timeline article div.inner span.date {
        display: block;
        width: 60px;
        height: 50px;
        padding: 5px 0;
        position: absolute;
        top: 0;
        left: 50%;
        margin: 0 0 0 -32px;
        border-radius: 100%;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        background: white;
        /* background: #25303B;
         color: rgba(255,255,255,0.5);
         border: 2px solid rgba(255,255,255,0.2);
         box-shadow: 0 0 0 7px #25303B;*/
    }
    section#timeline article div.inner span.date span {
        display: block;
        text-align: center;
    }
    section#timeline article div.inner span.date span.day {
        font-size: 10px;
    }
    section#timeline article div.inner span.date span.month {
        font-size: 18px;
    }
    section#timeline article div.inner span.date span.year {
        font-size: 10px;
    }
    section#timeline article div.inner h2 {
        padding: 15px;
        margin: 0;
        color: #fff;
        font-size: 20px;
        text-transform: uppercase;
        letter-spacing: -1px;
        border-radius: 6px 6px 0 0;
        position: relative;
    }
    section#timeline article div.inner h2:after {
        content: '';
        position: absolute;
        top: 20px;
        right: -5px;
        width: 10px;
        height: 10px;
        -webkit-transform: rotate(-45deg);
    }
    section#timeline article div.inner p {
        padding: 15px;
        margin: 0;
        font-size: 14px;
        background: #fff;
        color: #656565;
        border-radius: 0 0 6px 6px;
    }


    section#timeline article:nth-child(2n+2) div.inner {
        /* float: right;*/
    }
    section#timeline article:nth-child(2n+2) div.inner h2:after {
        /*left: -5px;*/
    }


    section#timeline  article div.inner.sent {
        float: right;
    }
    section#timeline article div.inner.sent h2:after {
        left: -5px;
    }


    section#timeline article  div.inner h2 {
        background: #e74c3c;
    }
    section#timeline article  div.inner h2:after {
        background: #e74c3c;
    }


    section#timeline article:nth-child(1) div.inner h2 {
        background: #e74c3c;
    }
    section#timeline article:nth-child(1) div.inner h2:after {
        background: #e74c3c;
    }
    section#timeline article:nth-child(2) div.inner h2 {
        background: #2ecc71;
    }
    section#timeline article:nth-child(2) div.inner h2:after {
        background: #2ecc71;
    }
    section#timeline article:nth-child(3) div.inner h2 {
        background: #e67e22;
    }
    section#timeline article:nth-child(3) div.inner h2:after {
        background: #e67e22;
    }
    section#timeline article:nth-child(4) div.inner h2 {
        background: #1abc9c;
    }
    section#timeline article:nth-child(4) div.inner h2:after {
        background: #1abc9c;
    }
    section#timeline article:nth-child(5) div.inner h2 {
        background: #9b59b6;
    }
    section#timeline article:nth-child(5) div.inner h2:after {
        background: #9b59b6;
    }


    .overme {
        overflow:hidden;
        white-space:nowrap;
        text-overflow: ellipsis;
        max-width:300px;
    }




</style>
