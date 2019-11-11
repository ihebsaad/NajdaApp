
@extends('layouts.mainlayout')

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@section('content')
     <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.1/select2.min.js"></script>-->


    <section class="content form_layouts">

        <div class="container-fluid">
    <div class="row" style="margin-top:10px">
        <div class="col-lg-12">
            <ul id="tabs" class="nav  nav-tabs"  data-tabs="tabs">
                <li class=" nav-item active">
                    <a class="nav-link active   " href="#tab01" data-toggle="tab" onclick="showinfos();hideinfos2();hideinfos3();" >
                        <i class="fas fa-lg fa-user-md"></i>  Détails de l'intervenant
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab02" data-toggle="tab"  onclick=";showinfos2();hideinfos();hideinfos3();;">
                        <i class="fas fa-lg fa-ambulance"></i>  Prestations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab03" data-toggle="tab"  onclick="showinfos3();hideinfos();hideinfos2();">
                        <i class="fas fa-lg fa-sort-amount-down"></i>  Priorités
                    </a>
                </li>

            </ul>

        </div>
    </div>

 <div id="tab01" class="tab-pane fade active in    "  style="padding-top:30px">

     <form id="updateform">
         {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Civilité</label>
                                <select onchange="changing(this)"   class="form-control input" name="civilite" id="civilite"  value="{{ $prestataire->civilite }}">
                                    <option <?php if ($prestataire->civilite ==''){echo 'selected="selected"';} ?>></option>
                                    <option <?php if ($prestataire->civilite =='Mr'){echo 'selected="selected"';} ?> value="Mr">Mr</option>
                                    <option <?php if ($prestataire->civilite =='Mme'){echo 'selected="selected"';} ?>value="Mme">Mme</option>
                                    <option <?php if ($prestataire->civilite =='Mlle'){echo 'selected="selected"';} ?>value="Mlle">Mlle</option>
                                    <option <?php if ($prestataire->civilite =='Dr'){echo 'selected="selected"';} ?>value="Dr">Dr</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="name" id="name"  value="{{ $prestataire->name }}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Prénom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="prenom" id="prenom"  value="{{ $prestataire->prenom }}">
                            </div>
                        </div>
                    </div>
        <!-- <div class="row">

         <div class="col-md-6">
             <div class="form-group">
                 <div class="form-group">
                     <label for="inputError" class="control-label">Spécialité *</label>
                     <input onchange="changing(this)" type="text" class="form-control input" name="specialite" id="specialite"  value={{ $prestataire->specialite }}>
                 </div>
             </div>
         </div>

         </div>-->

         <div class="row">


             <div class="form-group  ">
                 <label>Type de prestations</label>
                 <div class="row">
                     <select class="itemName form-control col-lg-6" style="width:100%" name="itemName"  multiple  id="typeprest" >
                         <option></option>
                         <?php if ( count($relations) > 0 ) { ?>

                         @foreach($relations as $prest  )
                             @foreach($typesprestations as $aKey)
                                 <option  @if($prest->type_prestation_id==$aKey->id)selected="selected"@endif    onclick="createtypeprest('tpr<?php echo $aKey->id; ?>')"  value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option>
                             @endforeach
                         @endforeach

                         <?php
                         } else { ?>
                         @foreach($typesprestations as $aKey)
                             <option    onclick="createtypeprest('tpr<?php echo $aKey->id; ?>')"  value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option>
                         @endforeach

                         <?php }  ?>

                     </select>

                 </div>
             </div>
         </div>

         <div class="form-group ">
             <label>Spécialités</label>

             <div class="row">
                  <select class="form-control  col-lg-12 itemName " style="width:100%" name="specialite"  multiple  id="specialite" onchange="location.reload();">

                     <option></option>
                     <?php if ( count($relations2) > 0 ) { ?>

                     @foreach($relations2 as $rel  )
                         @foreach($specialites2 as $sp)
                             <option  @if($rel->specialite==$sp->id)selected="selected"@endif    onclick="createspec('spec<?php echo $sp->id; ?>')"  value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                         @endforeach
                     @endforeach

                     <?php

                     }else{
                       foreach($specialites2 as $sp){ ?>
                          <option      onclick="createspec('spec<?php echo $sp->id; ?>')"  value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                      <?php }

                      }
                     ?>

                 </select>

             </div>
         </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="form_control_1">Observation  <span class="required"> * </span></label>
                                <textarea onchange="changing(this)" rows="2" class="form-control" name="observation_prestataire" id="observation_prestataire"> {{$prestataire->observation_prestataire}} </textarea>
                            </div>
                        </div>
                    </div>




                    <div class="row">
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gouvernorat de couverture</label>
                                    <select class="form-control col-lg-6" style="" name="gouv"  multiple  id="gouvcouv">
                                        <option></option>

                                        <?php if ( count($relationsgv) > 0 ) {?>

                                    @foreach($relationsgv as $Rgv  )
                                            @foreach($gouvernorats as $aKeyG)
                                                <option  @if($Rgv->citie_id==$aKeyG->id)selected="selected"@endif    value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                            @endforeach
                                        @endforeach

                                        <?php
                                        } else { ?>

                                        @foreach($gouvernorats as $aKeyG)
                                            <option     value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                        @endforeach

                                       <?php }  ?>
                                    </select>
                                </div>
                             </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ville du siège social</label><br>

                                <input onchange="changing(this)" type="text" class="form-control input" name="ville" id="ville" value="{{ $prestataire->ville }}">

                            <?php
                                if ($prestataire->ville==''){
                                if ($prestataire->ville_id >0)
                                    {

                                        $villeid=intval($prestataire['ville_id']);
                                        if (isset($villes[$villeid]['name']) ){$nomv=$villes[$villeid]['name'];}
                                        else{$nomv=$prestataire['ville'];}

                                        echo '<label style="font-weight:bold">'. $nomv .'</label>';

                                        ?>

                                 <?php    }  }?>


                                <script>
                                    var placesAutocomplete = places({
                                        appId: 'plCFMZRCP0KR',
                                        apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                        container: document.querySelector('#ville')
                                    });
                                </script>
                             </div>
                        </div>


                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Adresse </label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="adresse" id="adresse"  value="{{ $prestataire->adresse }}">
                            </div>
                        </div>
                        <?php if ($prestataire->fax!='') {?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Fax</label>
                                <input onchange="changing(this)" type="text" id="fax" class="form-control" name="fax"  value="{{ $prestataire->fax }}">
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="row">
                        <?php if ($prestataire->phone_cell!='') {?>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Mobile 1</label>
                                <input onchange="changing(this)" type="text" id="phone_cell" class="form-control" name="phone_cell"  value="{{ $prestataire->phone_cell }}">
                            </div>
                        </div>
                            <?php } if ($prestataire->phone_cell2!='') {?>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Mobile 2 </label>
                                <input onchange="changing(this)" type="text" id="phone_cell2" class="form-control" name="phone_cell2"  value="{{ $prestataire->phone_cell2 }}">
                            </div>
                        </div>
                            <?php } ?>
                    </div>

         <div class="row">
             <?php   if ($prestataire->phone_home!='') {?>

             <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Téléphone 1</label>
                                <input onchange="changing(this)" type="text" id="phone_home" class="form-control" name="phone_home"  value="{{ $prestataire->phone_home }}">
                            </div>
                        </div>
                 <?php } if ($prestataire->phone_home2!='') { ?>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Téléphone 2</label>
                                <input onchange="changing(this)" type="text" id="phone_home2" class="form-control" name="phone_home2"  value="{{ $prestataire->phone_home2 }}">
                            </div>
                        </div>
                 <?php } ?>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php if($prestataire->mail!=''){ ?>  <label for="inputError" class="control-label">Email </label><?php } ?>
                                <?php if($prestataire->mail!=''){ ?><input onchange="changing(this)"  type="text" id="mail" class="form-control" name="mail" placeholder="Email"  value="{{ $prestataire->mail }}"> <br><?php }?>

                                <?php if($prestataire->mail2!=''){ ?>   <input onchange="changing(this)"  type="text" id="mail2" name="mail2" class="form-control" placeholder="Email2"  value="{{ $prestataire->mail2 }}"><br> <?php }?>

                                <?php if($prestataire->mail3!=''){ ?>   <input onchange="changing(this)" type="text" id="mail3"  name="mail3" class="form-control" placeholder="Email3"  value="{{ $prestataire->mail3 }}"><br> <?php }?>

                                <?php if($prestataire->mail4!=''){ ?>   <input onchange="changing(this)" type="text" id="mail4" name="mail4" class="form-control" placeholder="Email4"  value="{{ $prestataire->mail4 }}"><br> <?php }?>

                                <?php if($prestataire->mail5!=''){ ?>   <input onchange="changing(this)" type="text" id="mail5"  name="email5" class="form-control" placeholder="Email5"  value="{{ $prestataire->mail5 }}"><br> <?php }?>

                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <label style="padding-top:10px">Actif</label>
                                </div>
                                <div class="radio-list">
                                    <div class="col-md-3">
                                        <label for="annule" class="">
                                            <div class="radio" id="uniform-actif"><span class="checked">
                                                <input  onclick="changing(this)" type="radio" name="annule" id="annule" value="0"   <?php if ($prestataire->annule ==0){echo 'checked';} ?>></span></div> Oui
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nonactif" class="">
                                            <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('annule')" type="radio" name="annule" id="nonactif" value="1"  <?php if ($prestataire->annule ==1){echo 'checked';} ?>></span></div> Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
     </form>

                <div class="row form-group">


                    <div class="row" style="margin-top:30px">
                        <div class="col-md-8">
                            <h4><i class="fa fa-lg fa-phone"></i>  Numéros de Téléphones</h4>
                        </div>
                        <div class="col-md-4">
                            <button style="float:right" id="add1" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding1"><b><i class="fa fa-phone"></i> Ajouter un numéro Tel</b></button>
                        </div>

                    </div>
                    <table class="table table-striped"  style="width:100%;margin-top:35px;margin-bottom:35px;font-size:16px;">
                        <thead>
                        <tr class="headtable">
                            <th style="width:35%">Nom et Prénom</th>
                            <th style="width:20%">Tel</th>
                            <th style="width:30%">Remarque</th>
                            <th style="width:10%">Type</th>
                            <th style="width:5%">Supp</th>

                        </tr>

                        </thead>
                        <tbody>
                        @foreach($tels as $tel)
                            <tr>
                                <td style="width:35%;"><input id='tel-nom-<?php echo $tel->id;?>'  style="width:50%" value="<?php echo $tel->nom; ?>" onchange="changingAddress('<?php echo $tel->id; ?>','nom',this)" /> <input  id='tel-prenom-<?php echo $tel->id;?>'   style="width:48%" value="<?php echo $tel->prenom; ?>" onchange="changingAddress('<?php echo $tel->id; ?>','prenom',this)" /></td>
                                <td style="width:20%;"><input type="text" pattern="[0-9]" id='tel-champ-<?php echo $tel->id;?>'  style="width:100%" value="<?php echo $tel->champ; ?>" onchange="changingAddress('<?php echo $tel->id; ?>','champ',this)" /></td>
                                <td style="width:30%;"><input   id='tel-rem-<?php echo $tel->id;?>'   style="width:100%" value="<?php echo $tel->remarque; ?>" onchange="changingAddress('<?php echo $tel->id; ?>','remarque',this)" /></td>
                                <td style="width:10%;"><select  id='tel-typetel-<?php echo $tel->id;?>'   style="width:100%"  onchange="changingAddress('<?php echo $tel->id; ?>','typetel',this)" ><option <?php  if($tel->typetel=='Fixe'){echo 'selected="selected"';} ?> value="Fixe">Fixe</option><option <?php  if($tel->typetel=='Mobile'){echo 'selected="selected"';} ?> value="Mobile">Mobile</option></option></select> <?php if($tel->typetel=='Mobile') {?> <a onclick="setTel(this);" class="<?php echo $tel->champ;?>" style="margin-left:5px;cursor:pointer" data-toggle="modal"  data-target="#sendsms" ><i class="fas fa-sms"></i> Envoyer un SMS </a><?php } ?>
                                <td style="width:5%;">
                                    <a  href="{{action('ClientsController@deleteaddress', $tel->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                        <span class="fa fa-fw fa-trash-alt"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>


                    <div class="row" style="margin-top:30px">
                        <div class="col-md-8">
                            <h4><i class="fa fa-lg fa-envelope"></i>  Adresses Emails </h4>
                        </div>
                        <div class="col-md-4">
                            <button style="float:right" id="add2" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding2"><b><i class="fa fa-envelope"></i> Ajouter une adresse email</b></button>
                        </div>

                    </div>
                    <table class="table table-striped"  style="width:100%;margin-top:35px;margin-bottom:35px;font-size:16px;">
                        <thead>
                        <tr class="headtable">
                            <th style="width:35%">Nom</th>
                            <th style="width:20%">Email</th>
                             <th style="width:30%">Remarque</th>
                            <th style="width:10%">Contacter</th>
                            <th style="width:5%">Supp</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($emails as $email)
                            <tr>
                                <td style="width:35%;"><input id='email-nom-<?php echo $email->id;?>'  style="width:50%" value="<?php echo $email->nom; ?>" onchange="changingAddress('<?php echo $email->id; ?>','nom',this)" /> <input  id='email-prenom-<?php echo $email->id;?>'   style="width:48%" value="<?php echo $email->prenom; ?>" onchange="changingAddress('<?php echo $email->id; ?>','prenom',this)" /></td>
                                <td style="width:20%;"><input type="email"  id='email-champ-<?php echo $email->id;?>'   style="width:100%" value="<?php echo $email->champ; ?>" onchange="changingAddress('<?php echo $email->id; ?>','champ',this)" /></td>
                                 <td style="width:30%;"><input   id='email-rem-<?php echo $email->id;?>'   style="width:100%" value="<?php echo $email->remarque; ?>" onchange="changingAddress('<?php echo $email->id; ?>','remarque',this)" /></td>
                                <td style="width:10%;"><i class="fa fa-envelope"></i></td>
                                <td style="width:5%;">
                                    <a  href="{{action('ClientsController@deleteaddress', $email->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                        <span class="fa fa-fw fa-trash-alt"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                    <div class="row" style="margin-top:30px">
                        <div class="col-md-8">
                            <h4><i class="fa fa-lg fa-fax"></i>  Numéros de Fax </h4>
                        </div>
                        <div class="col-md-4">
                            <button style="float:right" id="add3" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding3"><b><i class="fa fa-fax"></i> Ajouter un numéro de fax</b></button>
                        </div>

                    </div>

                    <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                        <thead>
                        <tr class="headtable">
                            <th style="width:35%">Nom</th>
                            <th style="width:20%">Fax</th>
                             <th style="width:30%">Remarque</th>
                            <th style="width:10%">Contacter</th>
                            <th style="width:5%">Supp</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($faxs as $fax)
                            <tr>
                                <td style="width:35%;"><input id='fax-nom-<?php echo $fax->id;?>'  style="width:50%" value="<?php echo $fax->nom; ?>" onchange="changingAddress('<?php echo $fax->id; ?>','nom',this)" /> <input  id='fax-prenom-<?php echo $tel->id;?>'   style="width:48%" value="<?php echo $fax->prenom; ?>" onchange="changingAddress('<?php echo $fax->id; ?>','prenom',this)" /></td>
                                <td style="width:20%;"><input type="text" pattern="[0-9]" id='fax-champ-<?php echo $fax->id;?>'   style="width:100%" value="<?php echo $fax->champ; ?>" onchange="changingAddress('<?php echo $fax->id; ?>','champ',this)" /></td>
                                 <td style="width:30%;"><input   id='fax-rem-<?php echo $fax->id;?>'   style="width:100%" value="<?php echo $fax->remarque; ?>" onchange="changingAddress('<?php echo $fax->id; ?>','remarque',this)" /></td>
                                <td style="width:10%;"><i class="fa fa-fax"></i></td>
                                <td style="width:5%;">
                                    <a  href="{{action('ClientsController@deleteaddress', $fax->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                        <span class="fa fa-fw fa-trash-alt"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>




                </div>


   <input type="hidden" id="idpres" class="form-control"   value="{{ $prestataire->id }}">

        </div>

    <div id="tab02" class="tab-pane fade in  " style="padding-top:30px;display:none;">


        <table class="table table-striped" id="mytable" style="width:100%">
        <thead>
        <tr id="headtable">
            <th style="width:10%">Dossier</th>
            <th style="width:20%">Prestataire</th>
            <th style="width:20%">Type</th>
            <th style="width:20%">Spécialité</th>
            <th style="width:20%">Gouvernorat</th>
            <th style="width:10%">Prix</th>
        </tr>

        </thead>
        <tbody>
        <?php use \App\Http\Controllers\PrestationsController;     ?>

        @foreach($prestations as $prestation)
            <?php $dossid= $prestation['dossier_id'];?>
            <?php $effectue= $prestation['effectue'];
            if($effectue ==0){$style='background-color:#fcdcd5;';}else{$style='';}
            ?>

            <tr  >
                <td style="width:35%; <?php echo $style;?> ">
               <a href="{{action('PrestationsController@view', $prestation['id'])}}" >
                        <?php  echo PrestationsController::DossierById($dossid);  ?>
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



            <div id="tab03" class="tab-pane fade    " style="padding-top:30px">


                <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addev" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createeval"><b><i class="fas fa-plus"></i> Ajouter une Priorité</b></button>

                <table class="table table-striped" id="mytable2" style="width:100%">
                    <thead>
                    <tr id="headtable">
                        <th style="text-align: center;width:20%">Type de prestation</th>
                        <th style="text-align: center;width:20%">Spécialité</th>
                        <th style="text-align: center;width:20%">Gouvernorat</th>
                        <th style="text-align: center;width:20%">Ville</th>
                        <th style="text-align: center;width:10%">Priorité</th>
                     </tr>

                    </thead>
                    <tbody>
                 @foreach($evaluations as $eval)
                    <tr>
                        <td style="text-align: center;width:20%"><?php echo PrestationsController::TypePrestationById( $eval['type_prest']) ;?></td>
                        <td style="text-align: center;width:20%"><?php echo PrestationsController::SpecialiteById($eval['specialite']) ;?></td>
                        <td style="text-align: center;width:20%"><?php echo PrestationsController::GouvById( $eval['gouv']) ;?> </td>
                        <td style="text-align: center;width:20%"><?php echo   $eval['ville'] ;?> </td>

                        <td style="text-align: center;width:10%;"><?php echo $eval['priorite'] ;?></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>


            </div>

    </section>




    <!-- Modal Evaluation-->
    <div class="modal fade" id="createeval" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Ajouter une Priorité </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="addevalform" novalidate="novalidate">

                                <input id="prestataire_id" name="prestataire" type="hidden" value="{{ $prestataire->id}}">
                                <div class="form-group " >
                                    <label>Type de prestations *</label>
                                    <div class=" row  ">
                                        <select class=" form-control col-lg-12  " style="width:400px" name=""    id="typeprestation">
                                            <option></option>


                                            <?php if ( count($relations) > 0 ) {?>

                                            @foreach($relations as $prest  )
                                                @foreach($typesprestations as $aKey)
                                                <?php  if($prest->type_prestation_id==$aKey->id) { ?>    <option  value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option><?php }?>
                                                @endforeach
                                            @endforeach

                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label>Spécialité</label>
                                    <div class="row">
                                        <select class="form-control  col-lg-12 " style="width:400px" name="specialite2"    id="specialite2">
                                            <option></option>


                                            <?php if ( count($relations2) > 0 ) {?>

                                            @foreach($relations2 as $rel  )
                                                @foreach($specialites2 as $sp)
                                                    <?php if($rel->specialite==$sp->id) {  ?>   <option   value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option><?php } ?>
                                                @endforeach
                                            @endforeach

                                            <?php
                                            }
                                            ?>


                                        </select>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label>Gouvernorat de couverture *</label>
                                    <div class="row">
                                        <select class="form-control  col-lg-12 "  style="width:400px" name="gouv"    id="gouvpr">
                                            <option></option>

                                                <?php if ( count($relationsgv) > 0 ) {?>

                                                @foreach($relationsgv as $Rgv  )
                                                    @foreach($gouvernorats as $aKeyG)
                                                         <?php if($Rgv->citie_id==$aKeyG->id) {  ?>   <option   value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option> <?php } ?>
                                                    @endforeach
                                                @endforeach

                                                <?php
                                                }
                                                ?>
                                            </select>

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label>Ville</label>
                                    <div class="row">
                                        <input class="form-control col-lg-6" style="padding-left:5px;" type="text"  id="villepr" />
                                        <input class="form-control" style="padding-left:5px;" type="hidden"  id="villecode" />

                                    </div>
                                <script>
                                    (function() {
                                        var placesAutocomplete2 = places({
                                            appId: 'plCFMZRCP0KR',
                                            apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                            container: document.querySelector('#villepr'),

                                        });
                                        placesAutocomplete2.on('change', function resultSelected(e) {
                                              document.querySelector('#villecode').value = e.suggestion.postcode || '';
                                        });
                                    })();
                                    </script>
                                </div>
                                <div class="form-group ">
                                    <label>Priorité *</label>
                                    <div class="row">
                                        <input style="padding-left:5px;" type="number" step="1" id="prior" max="10" min="0" value="1" />
                                    </div>
                                </div>
                    <!--        <div class="form-group ">
                                    <label>Disponibilité</label>
                                    <div class="row">
                                        <input type="number" step="1" max="10" min="0" id="disp" value="0"  />
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label>Evaluation</label>
                                    <div class="row">
                                        <input type="number" step="1" max="10" min="0" id="note" value="0"  />
                                    </div>
                                </div>
                                -->
                            </form>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" id="evaladd" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>





    <!-- Modal Tels -->
    <div class="modal fade" id="adding1" tabindex="-1" role="dialog" aria-labelledby="exampleModal1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal1">Ajouter un numéro de téléphone </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form   id="ff" name="">
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
                                    <label for="adresse">Téléphone</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="number" required id="champ1"  onchange="checkexiste(this,'tel')"/>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="code">Type</label>
                                    <div class="row">
                                        <select    class="form-control"  id="typetel"  >
                                            <option value="Fixe">Fixe</option>
                                            <option value="Mobile">Mobile</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="code">Remarque</label>
                                    <div class="row">
                                        <textarea   class="form-control"  id="remarque1" ></textarea>

                                    </div>
                                </div>
                                <input id="nature1" name="nature" type="hidden" value="tel">



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



    <!-- Modal email -->
    <div class="modal fade" id="adding2" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Ajouter une adresse email </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form   id="ffll" name="">
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
                                    <label for="adresse">Email</label>
                                    <div class=" row  ">
                                        <input class="form-control" onchange="checkexiste(this,'mail')" type="email" required id="champ2"/>

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="code">Remarque</label>
                                    <div class="row">
                                        <textarea   class="form-control"  id="remarque2" ></textarea>

                                    </div>
                                </div>
                                <input id="nature2" name="nature" type="hidden" value="email">



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


    <!-- Modal Fax -->
    <div class="modal fade" id="adding3" tabindex="-1" role="dialog" aria-labelledby="exampleModal3" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal3">Ajouter un numéro de Fax </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form   id="fggf" name="">
                                {{ csrf_field() }}

                                <div class="form-group " >
                                    <label for="adresse">Nom</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="nomf"/>

                                    </div>
                                </div>
                                <div class="form-group " >
                                    <label for="adresse">Prénom</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="prenomf"/>

                                    </div>
                                </div>
                                <div class="form-group " >
                                    <label for="adresse">Fax</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="number" required id="champ3"   onchange="checkexiste(this,'fax')" />

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="code">Remarque</label>
                                    <div class="row">
                                        <textarea   class="form-control"  id="remarque3" ></textarea>

                                    </div>
                                </div>
                                <input id="nature3" name="nature" type="hidden" value="fax">



                            </form>
                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <span type="button" id="btnaddfax" class="btn btn-primary">Ajouter</span>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal SMS -->
    <div class="modal fade" id="sendsms" role="dialog" aria-labelledby="sendingsms" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal7">Envoyer un SMS </h5>

                </div>
                <form method="post"   >

                    <div class="modal-body">
                        <div class="card-body">


                            <div class="form-group">
                                {{ csrf_field() }}
                                <label for="description">Dossier:</label>

                                <div class="form-group">
                                    <select id ="ledossier"  class="form-control " style="width: 120px">
                                        <option></option>
                                        <?php foreach($dossiers as $ds)

                                        {
                                            echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' </option>';}     ?>
                                    </select>
                                </div>


                            </div>


                            <div class="form-group">
                                {{ csrf_field() }}
                                <label for="description">Description:</label>
                                <input id="ladescription" type="text" class="form-control" name="description"     />
                            </div>

                            <div class="form-group">

                                <label for="destinataire">Destinataire:</label>
                                <input id="ledestinataire" type="number" class="form-control" name="destinataire"      />
                            </div>

                            <div class="form-group">
                                <label for="contenu">Message:</label>
                                <textarea  type="text" class="form-control" name="lemessage"></textarea>
                            </div>
                        {{--  {!! NoCaptcha::renderJs() !!}     --}}
                        <!--  <script src="https://www.google.com/recaptcha/api.js" async defer></script>-->


                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <span   id="envoisms" class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</span>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
<style>.headtable{background-color: grey!important;color:white;}
    table{margin-bottom:40px;}
</style>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>



<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>


<script>







    function hideinfos() {
        $('#tab01').css('display','none');
    }
    function hideinfos2() {
        $('#tab02').css('display','none');
    }
    function hideinfos3() {
        $('#tab03').css('display','none');
    }
    function showinfos() {
        $('#tab01').css('display','block');
    }

    function showinfos2() {
        $('#tab02').css('display','block');
    }
    function showinfos3() {
        $('#tab03').css('display','block');
    }

        function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var prestataire = $('#idpres').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.updating') }}",
            method: "POST",
            data: {prestataire: prestataire , champ:champ ,val:val, _token: _token},
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



    function changingAddress(id,champ,elm) {
        var champid=elm.id;
        var val =document.getElementById(champid).value;

        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('clients.updateaddress') }}",
            method: "POST",
            data: {id: id , champ:champ ,val:val,  _token: _token},
            success: function (data) {
                $('#'+champid).animate({
                    opacity: '0.3',
                });
                $('#'+champid).animate({
                    opacity: '1',
                });            }
        });

    }


    function disabling(elm) {
        var champ=elm;

        var val =1;
         var prestataire = $('#idpres').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.updating') }}",
            method: "POST",
            data: {prestataire: prestataire , champ:champ ,val:val, _token: _token},
            success: function (data) {
                if (elm=='annule'){
                $('#nonactif').animate({
                    opacity: '0.3',
                });
                $('#nonactif').animate({
                    opacity: '1',
                });
                }


            }
        });
        // } else {

        // }
    }




    $(function () {
/*

        function removeprest(elm) {

            var id= elm.id;
            var typeprest= id.slice(5);
            var prestataire = $('#idpres').val();

            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{-- route('prestataires.removetypeprest') --}}",
                method: "POST",
                data: {prestataire: prestataire , typeprest:typeprest ,  _token: _token},
                success: function (data) {
                    $('#type'+typeprest).hide( "slow", function() {
                        // Animation complete.
                    });


                }
            });

        }


        function createtypeprest(id) {

            var typeprest= id.slice(3);


            var prestataire = $('#idpres').val();


            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{-- route('prestataires.createtypeprest') --}}",
                method: "POST",
                data: {prestataire: prestataire , typeprest:typeprest ,  _token: _token},
                success: function (data) {

                    location.reload();

                }
            });


        }
  */
        $('#envoisms').click(function(){
            var description = $('#ladescription').val();
            var destinataire = $('#ledestinataire').val();
            var message = $('#lemessagel').val();
            var dossier = $('#ledossier').val();


            if ((message != '') &&(destinataire!='')&&(description!=''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('emails.sendsms') }}",
                    method:"POST",
                    data:{description:description,destinataire:destinataire,message:message,dossier:dossier, _token:_token},
                    success:function(data){

                        alert('SMS Envoyé !');
                        // window.location =data;
                        $("#sendsms").modal('hide');

                    }
                });
            }else{
                // alert('ERROR');
            }
        });


        $('#specialite').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });
     //   $("#dossier").select2();



        var $topo1 = $('#specialite');

        var valArray0 = ($topo1.val()) ? $topo1.val() : [];

        $topo1.change(function() {
            var val0 = $(this).val(),
                numVals = (val0) ? val0.length : 0,
                changes;
            if (numVals != valArray0.length) {
                var longerSet, shortSet;
                (numVals > valArray0.length) ? longerSet = val0 : longerSet = valArray0;
                (numVals > valArray0.length) ? shortSet = valArray0 : shortSet = val0;
                //create array of values that changed - either added or removed
                changes = $.grep(longerSet, function(n) {
                    return $.inArray(n, shortSet) == -1;
                });

                UpdatingS(changes, (numVals > valArray0.length) ? 'selected' : 'removed');

            }else{
                // if change event occurs and previous array length same as new value array : items are removed and added at same time
                UpdatingS( valArray0, 'removed');
                UpdatingS( val0, 'selected');
            }
            valArray0 = (val0) ? val0 : [];
        });



        function UpdatingS(array, type) {
            $.each(array, function(i, item) {

                if (type=="selected"){


                    var prestataire = $('#idpres').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('prestataires.createspec') }}",
                        method: "POST",
                        data: {prestataire: prestataire , specialite:item ,  _token: _token},
                        success: function () {
                            $('.select2-selection').animate({
                                opacity: '0.3',
                            });
                            $('.select2-selection').animate({
                                opacity: '1',
                            });

                        }
                    });

                }

                if (type=="removed"){
                    var prestataire = $('#idpres').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('prestataires.removespec') }}",
                        method: "POST",
                        data: {prestataire: prestataire , specialite:item ,  _token: _token},
                        success: function () {
                            $( ".select2-selection--multiple" ).hide( "slow", function() {
                                // Animation complete.
                            });
                            $( ".select2-selection--multiple" ).show( "slow", function() {
                                // Animation complete.
                            });
                        }
                    });

                }

            });
        } // updating






        $('#typeprest').select2({
            filter: true,
        language: {
            noResults: function () {
                return 'Pas de résultats';
            }
        }

        });



        var $topo = $('#typeprest');

        var valArray = ($topo.val()) ? $topo.val() : [];

        $topo.change(function() {
            var val = $(this).val(),
                numVals = (val) ? val.length : 0,
                changes;
            if (numVals != valArray.length) {
                var longerSet, shortSet;
                if(numVals > valArray.length) { longerSet = val }else{ longerSet = valArray;}
               // (numVals > valArray.length) ? longerSet = val : longerSet = valArray;
                (numVals > valArray.length) ? shortSet = valArray : shortSet = val;
                //create array of values that changed - either added or removed
                changes = $.grep(longerSet, function(n) {
                    return $.inArray(n, shortSet) == -1;
                });

                Updating(changes, (numVals > valArray.length) ? 'selected' : 'removed');

            }else{
                // if change event occurs and previous array length same as new value array : items are removed and added at same time
                Updating( valArray, 'removed');
                Updating( val, 'selected');
            }
            valArray = (val) ? val : [];
        });



        function Updating(array, type) {

            $.each(array, function(i, item) {

                if (type=="selected"){
                    alert('selected' +item);

                    var prestataire = $('#idpres').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('prestataires.createtypeprest') }}",
                        method: "POST",
                        data: {prestataire: prestataire , typeprest:item ,  _token: _token},
                        success: function (data) {
                            if(data==1){
                                $('.select2-selection').animate({
                                    opacity: '0.3',
                                });
                                $('.select2-selection').animate({
                                    opacity: '1',
                                });

                                location.reload();
                            }

                        }
                    });

                }

                if (type=="removed"){ ////
                    alert('removed' +item);

                    // alert(item);
                     var prestataire = $('#idpres').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('prestataires.removetypeprest') }}",
                        method: "POST",
                        data: {prestataire: prestataire , typeprest:item ,  _token: _token},
                        success: function () {
                            $( ".select2-selection--multiple" ).hide( "slow", function() {
                                // Animation complete.
                            });
                            $( ".select2-selection--multiple" ).show( "slow", function() {
                                // Animation complete.
                            });
                            location.reload();

                        }
                    });

                }

            });
        } // updating


        $('#gouvcouv').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });


        var $gouv = $('#gouvcouv');

        var valArray = ($gouv.val()) ? $gouv.val() : [];

        $gouv.change(function() {
            var val = $(this).val(),
                numVals = (val) ? val.length : 0,
                changes;
            if (numVals != valArray.length) {
                var longerSet, shortSet;
                (numVals > valArray.length) ? longerSet = val : longerSet = valArray;
                (numVals > valArray.length) ? shortSet = valArray : shortSet = val;
                //create array of values that changed - either added or removed
                changes = $.grep(longerSet, function(n) {
                    return $.inArray(n, shortSet) == -1;
                });

                UpdatingG(changes, (numVals > valArray.length) ? 'selected' : 'removed');

            }else{
                // if change event occurs and previous array length same as new value array : items are removed and added at same time
                UpdatingG( valArray, 'removed');
                UpdatingG( val, 'selected');
            }
            valArray = (val) ? val : [];
        });


        function UpdatingG(array, type) {
            $.each(array, function(i, item) {

                if (type=="selected"){


                    var prestataire = $('#idpres').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('prestataires.createcitieprest') }}",
                        method: "POST",
                        data: {prestataire: prestataire , citie:item ,  _token: _token},
                        success: function () {
                            $('.select2-selection').animate({
                                opacity: '0.3',
                            });
                            $('.select2-selection').animate({
                                opacity: '1',
                            });

                        }
                    });

                }

                if (type=="removed"){

                    var prestataire = $('#idpres').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('prestataires.removecitieprest') }}",
                        method: "POST",
                        data: {prestataire: prestataire , citie:item ,  _token: _token},
                        success: function () {
                            $( ".select2-selection--multiple" ).hide( "slow", function() {
                                // Animation complete.
                            });
                            $( ".select2-selection--multiple" ).show( "slow", function() {
                                // Animation complete.
                            });
                        }
                    });

                }

            });
        } // updating


        $('#evaladd').click(function(){
            var prestataire = $('#prestataire_id').val();
            var type_prest = $('#typeprestation').val();
            var gouvernorat = $('#gouvpr').val();
            var priorite = $('#prior').val();
            var ville = $('#villepr').val();
            var postal = $('#villecode').val();
         //   var disponibilite = $('#disp').val();
           // var evaluation = $('#note').val();
            var specialite = $('#specialite2').val();
             if ((type_prest != '') &&(gouvernorat != '')  &&(priorite != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('prestataires.addeval') }}",
                    method:"POST",
                    data:{prestataire:prestataire,type_prest:type_prest,gouvernorat:gouvernorat,priorite:priorite,specialite:specialite,ville:ville,postal:postal, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;


                    }
                }).fail(function() {
                alert("erreur lors de l'ajout, vérifier si la priorité existe deja ");
            });
                ;
            }else{
                // alert('ERROR');
                  alert('insérez les valeurs obligatoires');
            }
        })




        $('#btnaddtel').click(function(){

            var parent = $('#idpres').val();
            var nom = $('#nomt').val();
            var prenom = $('#prenomt').val();
            var champ = $('#champ1').val();
            var remarque = $('#remarque1').val();
            var nature = $('#nature1').val();
            var typetel = $('#typetel').val();
            if ((champ != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('prestataires.addressadd') }}",
                    method:"POST",
                    data:{nom:nom,prenom:prenom,parent:parent,champ:champ,remarque:remarque,nature:nature,typetel:typetel, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                // alert('ERROR');
            }
        });

        $('#btnaddemail').click(function(){
            var nom = $('#nome').val();
            var prenom = $('#prenome').val();
            var parent = $('#idpres').val();
            var champ = $('#champ2').val();
            var remarque = $('#remarque2').val();
            var nature = $('#nature2').val();
            if ((champ != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('prestataires.addressadd') }}",
                    method:"POST",
                    data:{nom:nom,prenom:prenom,parent:parent,champ:champ,remarque:remarque,nature:nature, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                // alert('ERROR');
            }
        });


        $('#btnaddfax').click(function(){
            var nom = $('#nomf').val();
            var prenom = $('#prenomf').val();
            var parent = $('#idpres').val();
            var champ = $('#champ3').val();
            var remarque = $('#remarque3').val();
            var nature = $('#nature3').val();
            if ((champ != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('prestataires.addressadd') }}",
                    method:"POST",
                    data:{nom:nom,prenom:prenom,parent:parent,champ:champ,remarque:remarque,nature:nature, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                // alert('ERROR');
            }
        });


        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-item a[href="#' + url.split('#')[1] + '"]').tab('show');
        }

// Change hash for page-reload
        $('.nav-item a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        })




    });



    function setTel(elm)
    {
        var num=elm.className;
        document.getElementById('ledestinataire').value=parseInt(num);

    }



    function checkexiste( elm,type) {
        var id=elm.id;
        var val =document.getElementById(id).value;
        //  var type = $('#type').val();

        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.checkexiste') }}",
            method: "POST",
            data: {   val:val,type:type, _token: _token},
            success: function (data) {

                if(data>0){
                    alert('  Existe deja !');
                    document.getElementById(id).style.background='#FD9883';
                    document.getElementById(id).style.color='white';
                } else{
                    document.getElementById(id).style.background='white';
                    document.getElementById(id).style.color='black';
                }


            }
        });
        // } else {

        // }
    }

    $(document).ready(function() {
        $("#typeprest").select2();
        $("#typeprest").data('originalvalues', []);
        $("#typeprest").on('change', function(e) {
            var that = this;
            removed = []

            $($(this).data('originalvalues')).each(function(k, v) {
                if (!$(that).val()) {
                    removed[removed.length] = v;
                    return false;
                }
                if ($(that).val().indexOf(v) == -1) {
                    removed[removed.length] = v;
                }
            });


            if ($(this).val()) {
                $(this).data('originalvalues', $(this).val());
            } else {
                $(this).data('originalvalues', []);
            }

            alert("-- Removed: " + removed)
        });
    });

</script>
