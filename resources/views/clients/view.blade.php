@extends('layouts.adminlayout')
<?php
$param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
?>
@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<!--
    <script src="{{  URL::asset('public/js/upload_files/vpb_uploader.js') }}" type="text/javascript"></script>


<!--
    <script type="text/javascript">
        $(document).ready(function()
        {
            // Call the main function
            new vpb_multiple_file_uploader
            ({
                vpb_form_id: "theform", // Form ID
                autoSubmit: true,
                vpb_server_url: "upload.php"
            });
        });
    </script>
-->
    <link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />

    <?php use \App\Http\Controllers\ClientsController;     ?>


    <div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">

    <div id="addclientform">
        <div class="portlet box grey">
            <div class="modal-header"><h4>Client</h4></div>
        </div>
        <div class="modal-body">
           <!-- <div class="row"  style="margin-bottom:30px">
                <div class="col-md-3">
                    <a  ><i class="fa fa-envelope"></i> Email </a>
                </div>
                <div class="col-md-3">
                    <a  >  <i class="fas fa-sms"></i> SMS </a>
                </div>
                <div class="col-md-3">
                    <a  ><i class="fa fa-fax"></i> Fax </a>

                </div>
                <div class="col-md-3">

                </div>
            </div>-->

            <div class="row"  style="margin-bottom:30px">
            <div class="col-md-2">
                <a href="{{action('ClientsController@dossiers', $client['id'])}}" >Total Dossiers  : <?php echo ClientsController::CountDossCL( $client['id']); ?> </a> | (<a href="{{action('ClientsController@ouverts', $client['id'])}}" > Ouverts : <?php echo ClientsController::CountDossCLouverts( $client['id']); ?> </a>)
            </div>
                <div class="col-md-2">
                   Mixtes : <?php echo ClientsController::CountDossCLMixte( $client['id']); ?>
                </div>
                <div class="col-md-2">
                    Médicaux : <?php echo ClientsController::CountDossCLMedic( $client['id']); ?>
                </div>
                <div class="col-md-2">
                    Techniques : <?php echo ClientsController::CountDossCLTechnique( $client['id']); ?>
                </div>
                <div class="col-md-2">
                    Transport : <?php echo ClientsController::CountDossCLTransp( $client['id']); ?>
                </div>

            </div>


			
			
	
 <form    action="{{action('ClientsController@view2')}}" >
	<div class="row">
    <input id="id"   type="hidden" name="id"   value="<?php echo $client['id'] ;?>"   />

 
  <div class="form-group col-xs-12 col-md-3">
<label for="debut">Début:</label>
    <input id="debut"  autocomplete="off" placeholder="jj-mm-aaaa" class="form-control datepicker" name="debut" required value="" format='jj-mm-aaaa' />
  </div>

  <div class="form-group col-xs-12 col-md-3">
<label for="fin">Fin:</label>
    <input id="fin"  autocomplete="off" placeholder="jj-mm-aaaa" class="form-control datepicker" name="fin" required value="" format='jj-mm-aaaa' />
  </div>
	
	
 <div class="form-group col-xs-12 col-md-3">
   <label></label> 
	 <input  type="submit" class="form-control btn btn-success"  value="Voir" style="margin-top:20px" />
  </div>
	 
</div>
	  </form>
	  <?php if (isset($debut) && isset($fin)) {
		$debut=$_GET['debut'];
		$fin=$_GET['fin'];
		echo 'Debut : ' .$debut;
		
	  ?>
	            <div class="row"  style="margin-bottom:30px">
            <div class="col-md-2">
                <a href="{{action('ClientsController@dossiers', $client['id'])}}" >Total Dossiers Par date  : <?php echo ClientsController::CountDossCLDate( $client['id'],$debut,$fin); ?> </a> | (<a href="{{action('ClientsController@ouverts', $client['id'])}}" > Ouverts : <?php echo ClientsController::CountDossCLouvertsDate( $client['id'],$debut,$fin); ?> </a>)
            </div>
                <div class="col-md-2">
                   Mixtes : <?php echo ClientsController::CountDossCLMixteDate( $client['id'],$debut,$fin); ?>
                </div>
                <div class="col-md-2">
                    Médicaux : <?php echo ClientsController::CountDossCLMedicDate( $client['id'],$debut,$fin); ?>
                </div>
                <div class="col-md-2">
                    Techniques : <?php echo ClientsController::CountDossCLTechniqueDate( $client['id'],$debut,$fin); ?>
                </div>
                <div class="col-md-2">
                    Transport : <?php echo ClientsController::CountDossCLTranspDate( $client['id'],$debut,$fin); ?>
                </div>

            </div>		
			
	  <?php } ?>	
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Nom *</label>
                        <input onchange="changing(this)"  type="text" class="form-control input" name="name" id="name"   value="{{ $client->name }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nature</label>

                        <select   class="form-control select2-offscreen" name="nature[]" id="nature" multiple=""   >
                            <option <?php   if(strpos($client->nature ,'1')!==false) {echo 'selected="selected"';} ?> value="1">Assistance / Assurance</option>
                            <option <?php   if(strpos($client->nature ,'2')!==false) {echo 'selected="selected"';} ?>value="2">Avionneur</option>
                            <option <?php   if(strpos($client->nature ,'3')!==false) {echo 'selected="selected"';} ?>value="3">Pétrolier / apparenté</option>
                            <option <?php   if(strpos($client->nature ,'4')!==false) {echo 'selected="selected"';} ?>value="4">Clinique</option>
                            <option <?php   if(strpos($client->nature ,'5')!==false) {echo 'selected="selected"';} ?>value="5">Agence de voyage / Hôtel</option>
                            <option <?php   if(strpos($client->nature ,'6')!==false) {echo 'selected="selected"';} ?>value="6">Autre</option>
                        </select>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Groupe</label>
                        <select class="form-control" name="groupe" id="groupe" onchange="changing(this)"   value="{{ $client->groupe }}">
                            <option value="0"></option>
                            @foreach($groupes as $gr  )
                                <option
                                        @if($client->groupe==$gr->id)selected="selected"@endif   value="{{$gr->id}}">{{$gr->label}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Pays</label>
                        <?php if ( $client->pays !='') { ?>  <input class="form-control" type="text" name="pays" onchange="changing(this)" id="pays"  value="{{ $client->pays }}"> <?php } ?>
                        <select class="form-control" id="pays2" class="form-control" onchange="changing(this)" >
                            <option></option>
                            @foreach($countries as $pays  )
                                <option <?php if ($client->pays2 == $pays->country_name){echo 'selected="selected"';} ?> value="{{$pays->country_name }}">{{$pays->country_name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Langue 1</label>
                        <select onchange="changing(this)"  class="form-control" name="langue1" id="langue1"  value="{{ $client->langue1 }}">
                            <option <?php if ($client->langue1 =='0'){echo 'selected="selected"';} ?> value="0"></option>
                            <option <?php if ($client->langue1 =='francais'){echo 'selected="selected"';} ?>value="francais">Français</option>
                            <option <?php if ($client->langue1 =='anglais'){echo 'selected="selected"';} ?>  value="anglais">Anglais</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Langue 2</label>
                        <select onchange="changing(this)"  class="form-control" name="langue2" id="langue2"  value="{{ $client->langue2 }}">
                            <option  <?php if ($client->langue2 ==''){echo 'selected="selected"';} ?> value="0"></option>
                            <option  <?php if ($client->langue2 =='francais'){echo 'selected="selected"';} ?> value="francais">Français</option>
                            <option <?php if ($client->langue2 =='anglais'){echo 'selected="selected"';} ?> value="anglais">Anglais</option>
                            <option <?php if ($client->langue2 =='allemand'){echo 'selected="selected"';} ?> value="allemand">Allemand</option>
                            <option <?php if ($client->langue2 =='portugais'){echo 'selected="selected"';} ?> value="portugais">Portugais</option>
                            <option <?php if ($client->langue2 =='espagnole'){echo 'selected="selected"';} ?> value="espagnole">Espagnole</option>
                            <option <?php if ($client->langue2 =='italien'){echo 'selected="selected"';} ?> value="italien">Italien</option>
                            <option <?php if ($client->langue2 =='arabe'){echo 'selected="selected"';} ?> value="arabe">Arabe</option>
                            <option <?php if ($client->langue2 =='neerlandais'){echo 'selected="selected"';} ?> value="neerlandais">Néerlandais</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Actif</label>
                        <div class="radio-list">
                            <div class="col-md-3">
                                <label for="annule" class="">
                                    <div class="radio" id="uniform-actif"><span  >
                                      <input  onclick="changing(this)" type="radio" name="annule" id="annule" value="0"   <?php if ($client->annule ==0){echo 'checked';} ?>></span></div> Oui
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="nonactif" class="">
                                    <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('annule')" type="radio" name="annule" id="nonactif" value="1"  <?php if ($client->annule ==1){echo 'checked';} ?>></span></div> Non
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>



        </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse">
                                    Opérations</a>
                            </h4>
                        </div>
                        <div class="panel-body">



            <div class="row">
                <?php if ($client->tel !='') { ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Téléphone </label> <input onchange="changing(this)"  type="text" class="form-control input" name="tel" id="tel" value="{{ $client->tel }}">
                    </div>
                </div>
                <?php  }  ?>
                 <?php   if ($client->tel2 !='') { ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Téléphone 2</label> <input onchange="changing(this)" type="text" id="tel2" class="form-control" name="tel2"  value="{{ $client->tel2 }}">
                    </div>
                </div>
                    <?php  }  ?>

            </div>


            <div class="row">
                <?php   if ($client->mail !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 1</label> <input onchange="changing(this)" type="text" id="mail" class="form-control" name="mail"  value="{{ $client->mail }}">
                    </div>
                </div>
                    <?php  }  ?>

                    <?php   if ($client->mail2 !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 2</label> <input onchange="changing(this)" type="text" id="mail2" class="form-control" name="email2" value="{{ $client->mail2 }}">
                    </div>
                </div>
                    <?php  }  ?>

                    <?php   if ($client->mail3 !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 3</label> <input onchange="changing(this)" type="text" id="mail3" class="form-control" name="email3" value="{{ $client->mail3 }}">
                    </div>
                </div>
                    <?php  }  ?>

            </div>

            <div class="row">
                <?php   if ($client->mail4 !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 4</label> <input onchange="changing(this)"  type="text" id="mail4" class="form-control" name="email4" value="{{ $client->mail4 }}">
                    </div>
                </div>
                    <?php  }  ?>

                    <?php   if ($client->mail5 !='') { ?>

                    <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 5</label> <input onchange="changing(this)" type="text" id="mail5" class="form-control" name="email5" value="{{ $client->mail5 }}">
                    </div>
                </div>
                    <?php  }  ?>

                    <?php   if ($client->mail6 !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 6</label> <input onchange="changing(this)" type="text" id="mail6" class="form-control" name="email6" value="{{ $client->mail6 }}">
                    </div>
                </div>
                    <?php  }  ?>

            </div>


            <div class="row">
                <?php   if ($client->mail7 !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 7</label> <input onchange="changing(this)" type="text" id="mail7" class="form-control" name="email7" value="{{ $client->mail7 }}">
                    </div>
                </div>
                    <?php  }  ?>

                    <?php   if ($client->mail8 !='') { ?>

                    <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 8</label> <input onchange="changing(this)"  type="text" id="mail8" class="form-control" name="email8" value="{{ $client->mail8 }}">
                    </div>
                </div>
                    <?php  }  ?>

                    <?php   if ($client->mail9 !='') { ?>

                    <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 9</label> <input onchange="changing(this)" type="text" id="mail9" class="form-control" name="email9" value="{{ $client->mail9 }}">
                    </div>
                </div>
                    <?php  }  ?>

            </div>

            <div class="row">
                <?php   if ($client->mail10 !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 10</label> <input onchange="changing(this)"  type="text" id="mail10" class="form-control" name="email10"  value="{{ $client->mail10 }}">

                    </div>
                </div>
                    <?php  }  ?>

            </div>

            <div class="row">
                <?php   if ($client->fax !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Fax 1</label> <input onchange="changing(this)" type="text" id="fax" class="form-control" name="fax" value="{{ $client->fax }}">
                    </div>
                </div>
                    <?php  }  ?>

                    <?php   if ($client->fax2 !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Fax 2</label> <input onchange="changing(this)"  type="text" id="fax2" class="form-control" name="fax2" value="{{ $client->fax2 }}">
                    </div>
                </div>
                    <?php  }  ?>

                    <?php   if ($client->fax3 !='') { ?>

                    <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Fax 3</label> <input onchange="changing(this)"  type="text" id="fax3" class="form-control" name="fax3" value="{{ $client->fax3 }}">
                    </div>
                </div>
                    <?php  }  ?>

            </div>

            <div class="row">
                <?php   if ($client->fax4 !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Fax 4</label> <input onchange="changing(this)" type="text" id="fax4" class="form-control" name="fax4" value="{{ $client->fax4 }}">
                    </div>
                </div>
                    <?php  }  ?>

                    <?php   if ($client->fax5 !='') { ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Fax 5</label> <input onchange="changing(this)" type="text" id="fax5" class="form-control" name="fax5" value="{{ $client->fax5 }}">
                    </div>
                </div>
                    <?php  }  ?>


            </div>

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
                                     <th style="width:20%">Tel</th>
                                    <th style="width:10%">Type</th>
                                    <th style="width:30%">Remarque</th>
                                    <th style="width:10%">Contacter</th>
                                    <th style="width:4%">Supp</th>
                                </tr>

                                </thead>

                                <tbody>
                                @foreach($tels as $tel)
                                    <tr>
                                         <td style="width:20%;"><input   id='tel-champ-<?php echo $tel->id;?>' type="text" pattern="[0-9]" style="width:100%" value="<?php echo $tel->champ; ?>" onchange="changingAddress('<?php echo $tel->id; ?>','champ',this)" /></td>
                                        <td style="width:10%;"><select  id='tel-type-<?php echo $tel->id;?>'   style="width:100%"   onchange="changingAddress('<?php echo $tel->id; ?>','type',this)" >
                                                <option <?php if($tel->type=='medical'){echo 'selected="selected"';} ?> value="medical">Médical</option>
                                                <option <?php if($tel->type=='technique'){echo 'selected="selected"';} ?> value="technique">Technique</option>
                                                <option <?php if($tel->type=='commun'){echo 'selected="selected"';} ?>value="commun">Commun</option></select></td>
                                        <td style="width:50%;"><input   id='tel-rem-<?php echo $tel->id;?>' style="width:100%" value="<?php echo $tel->remarque; ?>" onchange="changingAddress('<?php echo $tel->id; ?>','remarque',this)" /></td>
                                        <td style="width:10%;text-align:center;cursor:  pointer"><!--<i class="fa fa-phone"></i>-->  <a href="#" data-toggle="modal" data-target="#sendsms" onclick="setsms('<?php echo $tel->champ; ?>')" ><i class="fa fa-sms"></i> SMS</a></td>
                                        <td style="width:10%;">
                                            <a onclick="return confirm('Êtes-vous sûrs ?')" href="{{action('ClientsController@deleteaddress', $tel->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
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
                                     <th style="width:20%">Email</th>
                                    <th style="width:10%">Type</th>
                                    <th style="width:30%">Remarque</th>
                                    <th style="width:10%">Contacter</th>
                                    <th style="width:4%">Supp</th>

                                </tr>

                                </thead>
                                <tbody>
                                @foreach($emails as $email)
                                    <tr>
                                         <td style="width:20%;"><input type="email" id='email-champ-<?php echo $email->id;?>' style="width:100%" value="<?php echo $email->champ; ?>" onchange="changingAddress('<?php echo $email->id; ?>','champ',this)" /></td>
                                        <td style="width:10%;"><select  id='email-type-<?php echo $email->id;?>'   style="width:100%"   onchange="changingAddress('<?php echo $email->id; ?>','type',this)" >
                                                <option <?php if($email->type=='medical'){echo 'selected="selected"';} ?> value="medical">Médical</option>
                                                <option <?php if($email->type=='technique'){echo 'selected="selected"';} ?> value="technique">Technique</option>
                                                <option <?php if($email->type=='commun'){echo 'selected="selected"';} ?>value="commun">Commun</option></select></td>
                                        <td style="width:50%;"><input   id='email-rem-<?php echo $email->id;?>'  style="width:100%" value="<?php echo $email->remarque; ?>" onchange="changingAddress('<?php echo $email->id; ?>','remarque',this)" /></td>
                                        <td style="width:10%;text-align:center;"><a  href="#" data-toggle="modal" data-target="#sendmail" onclick="setmail('<?php echo $email->champ; ?>')"><i class="fa fa-envelope"></i> Email</a></td>
                                        <td style="width:10%;">
                                            <a onclick="return confirm('Êtes-vous sûrs ?')" href="{{action('ClientsController@deleteaddress', $email->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
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
                                    <th style="width:20%">Tel</th>
                                    <th style="width:10%">Type</th>
                                    <th style="width:50%">Remarque</th>
                                    <th style="width:10%">Contacter</th>
                                    <th style="width:4%">Supp</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($faxs as $fax)
                                    <tr>
                                        <td style="width:20%;"><input type="text" pattern="[0-9]"  id='fax-champ-<?php echo $fax->id;?>'   style="width:100%" value="<?php echo $fax->champ; ?>" onchange="changingAddress('<?php echo $fax->id; ?>','champ',this)" /></td>
                                        <td style="width:10%;"><select  id='fax-type-<?php echo $fax->id;?>'   style="width:100%"   onchange="changingAddress('<?php echo $fax->id; ?>','type',this)" ><option <?php if($fax->type=='medical'){echo 'selected="selected"';} ?> value="medical">Médical</option>
                                                <option <?php if($fax->type=='technique'){echo 'selected="selected"';} ?> value="technique">Technique</option>
                                                <option <?php if($fax->type=='commun'){echo 'selected="selected"';} ?>value="commun">Commun</option></select></td>
                                        <td style="width:50%;"><input  id='fax-rem-<?php echo $fax->id;?>'   style="width:100%" value="<?php echo $fax->remarque; ?>" onchange="changingAddress('<?php echo $fax->id; ?>','remarque',this)" /></td>
                                        <td style="width:10%;"><i class="fa fa-fax"></i></td>
                                        <td style="width:10%;">
                                            <a onclick="return confirm('Êtes-vous sûrs ?')" href="{{action('ClientsController@deleteaddress', $fax->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                                <span class="fa fa-fw fa-trash-alt"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>





           </div>
       </div>
     </div>
    </div>


                <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse">
                                    Coordonnées Back Office - Gestion</a>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?php   if ($client->gestion_mail1 !='') { ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 1</label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="gestion_mail1" id="gestion_mail1" value="{{ $client->gestion_mail1 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                                    <?php   if ($client->gestion_mail2 !='') { ?>

                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 2</label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="gestion_mail2" id="gestion_mail2"  value="{{ $client->gestion_mail2 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>


                            </div>
                            <div class="row">
                                <?php   if ($client->gestion_tel1 !='') { ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 1</label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="gestion_tel1" id="gestion_tel1"  value="{{ $client->gestion_tel1 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                                    <?php   if ($client->gestion_tel2 !='') { ?>

                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 2</label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="gestion_tel2" id="gestion_tel2"  value="{{ $client->gestion_tel2 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>


                            </div>
                            <div class="row">
                                <?php   if ($client->gestion_fax !='') { ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fax </label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="gestion_fax" id="gestion_fax"  value="{{ $client->gestion_fax }}">
                                    </div>
                                </div>

                                    <?php  }  ?>

                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Entité principale de facturation </label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="entite" id="entite"  value="<?php if($client->entite ==''){echo $client->name;}else{echo $client->entite; } ?>"  placeholder="<?php echo $client->name ;?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Adresse de facturation </label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="adresse" id="adresse"  value="{{ $client->adresse }}">
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-md-8">
                                    <h5>Entités de facturations différentes</h5>
                                </div>
                                <div class="col-md-4">
                                    <button style="float:right" id="add4" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding4"><b><i class="fa fa-map-marker"></i> Ajouter une entité à facturer</b></button>
                                </div>

                            </div>

                            <table class="table table-striped"  style="width:100%;margin-top:15px;font-size:16px;">
                                <thead>
                                <tr class="headtable">
                                    <th style="width:30%">Entité de facturation</th>
                                    <th style="width:60%">Adresse Postale complète</th>
                                    <th style="width:4%">Supp</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($entites as $entite)
                                    <tr>
                                        <td style="width:30%;"><input   id='entite-nom-<?php echo $entite->id;?>'  style="width:100%" value="<?php echo $entite->nom; ?>" onchange="changingAddress('<?php echo $entite->id; ?>','nom',this)" /></td>
                                        <td style="width:60%;"><input   id='entite-champ-<?php echo $entite->id;?>'  style="width:100%" value="<?php echo $entite->champ; ?>" onchange="changingAddress('<?php echo $entite->id; ?>','champ',this)" /></td>
                                        <td style="width:10%;">
                                            <a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('ClientsController@deleteaddress', $entite->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                                <span class="fa fa-fw fa-trash-alt"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>



                            <div class="form-group ">
                                <h3>Documents à signer</h3>

                                <div class="row">
                                    <select class="form-control  col-lg-12 itemName " style="width:400px" name="docs"  multiple  id="docs">


                                        <option></option>
                                        <?php if ( count($relations2) > 0 ) {?>

                                        @foreach($relations2 as $rel  )
                                            @foreach($docs as $doc)
                                                <option  @if($rel->doc==$doc->id)selected="selected"@endif    onclick="createspec('spec<?php echo $doc->id; ?>')"  value="<?php echo $doc->id;?>"> <?php echo $doc->nom;?></option>
                                            @endforeach
                                        @endforeach

                                        <?php
                                        } else { ?>
                                        @foreach($docs as $doc)
                                            <option    onclick="createspec('spec<?php echo $doc->id; ?>')"  value="<?php echo $doc->id;?>"> <?php echo $doc->nom;?></option>
                                        @endforeach

                                        <?php }  ?>

                                    </select>

                                </div>
                            </div>

								
	<?php							
	$groupe=$client->groupe;
	 
  	 $contratsg=DB::table('contrats_clients')->where('parent',$groupe)->where('type','commun')->count();
	if($contratsg==0){
	?>
                            <div class="form-group ">
                                <h3>Contrats</h3>


                                <div class="row">
                                    <select class="form-control  col-lg-12 itemName " style="width:400px" name="contrats"  multiple  id="contrats">


                                        <option></option>
                                        <?php if ( count($relaContr) > 0 ) {?>

                                        @foreach($relaContr as $relc  )
                                            @foreach($contrats as $contrat)
                                                <option  @if($relc->contrat==$contrat->id)selected="selected"@endif    onclick="createspec('spec<?php echo $contrat->id; ?>')"  value="<?php echo $contrat->id;?>"> <?php echo $contrat->nom;?></option>
                                            @endforeach
                                        @endforeach

                                        <?php
                                        } else { ?>
                                        @foreach($contrats as $contrat)
                                            <option    onclick="createspec('spec<?php echo $contrat->id; ?>')"  value="<?php echo $contrat->id;?>"> <?php echo $contrat->nom;?></option>
                                        @endforeach

                                        <?php }  ?>

                                    </select>

                                </div>
								
			 		
                            </div>

	  <?php }
					else{ echo '<center><h2>Client sous Contrats groupe</h2></center>';}


				  ?>
                            <div class="row" style="margin-top:40px">
                                <div class="col-md-8">
                                    <h4><i class="fa fa-lg fa-user"></i>  Responsables Gestion</h4>
                                </div>
                                <div class="col-md-4">
                                    <button style="float:right" id="add7" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding7"><b><i class="fa fa-user"></i> Ajouter un responsable gestion</b></button>
                                </div>

                            </div>

                            <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                                <thead>
                                <tr class="headtable">
                                    <th style="width:15%">Nom et Prénom</th>
                                    <th style="width:20%">Fonction</th>
                                    <th style="width:15%">Tel</th>
                                    <th style="width:15%">Fax</th>
                                    <th style="width:21%">Email</th>
                                    <th style="width:10%">Observation</th>
                                    <th style="width:4%">Supp</th>

                                </tr>

                                </thead>
                                <tbody>
                                @foreach($gestions as $gestion)
                                    <tr>
                                        <td style="width:15%;"><input placeholder="Nom"  id='gest-nom-<?php echo $gestion->id;?>'    style="width:100%" value="<?php echo $gestion->nom; ?>" onchange="changingAddress('<?php echo $gestion->id; ?>','nom',this)" /><br><input placeholder="Prenom"  id='gest-prenom-<?php echo $gestion->id;?>'  style="width:100%" value="<?php echo $gestion->prenom; ?>" onchange="changingAddress('<?php echo $gestion->id; ?>','prenom',this)" /></td>
                                        <td style="width:20%;"><input  id='gest-fon-<?php echo $gestion->id;?>'  style="width:100%" value="<?php echo $gestion->fonction; ?>" onchange="changingAddress('<?php echo $gestion->id; ?>','fonction',this)" /></td>
                                        <td style="width:15%;"><input type="text" id='gest-tel-<?php echo $gestion->id;?>'  style="width:100%" value="<?php echo $gestion->tel; ?>" onchange="changingAddress('<?php echo $gestion->id; ?>','tel',this)" />    <a  href="#" data-toggle="modal" data-target="#sendsms" onclick="setsms('<?php echo $gestion->tel; ?>')" >SMS <i class="fa fa-sms"></i></a></td>
                                        <td style="width:15%;"><input type="text" id='gest-fax-<?php echo $gestion->id;?>'  style="width:100%" value="<?php echo $gestion->fax; ?>" onchange="changingAddress('<?php echo $gestion->id; ?>','fax',this)" /></td>
                                        <td style="width:21%;"><input  type="email" id='gest-mail-<?php echo $gestion->id;?>'  style="width:100%" value="<?php echo $gestion->mail; ?>" onchange="changingAddress('<?php echo $gestion->id; ?>','mail',this)" /></td>
                                        <td style="width:10%;"><textarea  id='gest-rem-<?php echo $gestion->id;?>'  style="width:100%" onchange="changingAddress('<?php echo $gestion->id; ?>','remarque',this)"  > <?php echo $gestion->remarque; ?> </textarea></td>
                                        <td style="width:4%;">
                                            <a onclick="return confirm('Êtes-vous sûrs ?')" href="{{action('ClientsController@deleteaddress', $gestion->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                                <span class="fa fa-fw fa-trash-alt"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse">
                                    Coordonnées Back Office - Réclamation / Qualité</a>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?php   if ($client->qualite_mail1 !='') { ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 1</label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="qualite_mail1" id="qualite_mail1" value="{{ $client->qualite_mail1 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                                    <?php   if ($client->qualite_mail2 !='') { ?>

                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 2</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="qualite_mail2" id="qualite_mail2" value="{{ $client->qualite_mail2 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                            </div>
                            <div class="row">
                                <?php   if ($client->qualite_tel1 !='') { ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 1</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="qualite_tel1" id="qualite_tel1" value="{{ $client->qualite_tel1 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                                    <?php   if ($client->qualite_tel2 !='') { ?>

                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 2</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="qualite_tel2" id="qualite_tel2" value="{{ $client->qualite_tel2 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                            </div>
                            <div class="row">
                                <?php   if ($client->qualite_fax !='') { ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fax </label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="qualite_fax" id="qualite_fax" value="{{ $client->qualite_fax }}">
                                    </div>
                                </div>
                                    <?php  }  ?>


                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Adresse Postale </label>
                                        <textarea onchange="changing(this)"  type="text" class="form-control" name="adresse_qualite" id="adresse_qualite" value="{{ $client->adresse_qualite }}"> </textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Commentaire  </label>
                                        <textarea onchange="changing(this)"  type="text" class="form-control" name="commentaire_qualite" id="commentaire_qualite" value="{{ $client->commentaire_qualite }}"> </textarea>
                                    </div>
                                </div>

                            </div>


                            <div class="row" style="margin-top:40px">
                                <div class="col-md-8">
                                    <h4><i class="fa fa-lg fa-user"></i>  Responsables Qualité</h4>
                                </div>
                                <div class="col-md-4">
                                    <button style="float:right" id="add5" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding5"><b><i class="fa fa-user"></i> Ajouter un responsable Qualité</b></button>
                                </div>

                            </div>

                            <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                                <thead>
                                <tr class="headtable">
                                    <th style="width:15%">Nom et Prénom</th>
                                    <th style="width:20%">Fonction</th>
                                    <th style="width:15%">Tel</th>
                                    <th style="width:15%">Fax</th>
                                    <th style="width:21%">Email</th>
                                    <th style="width:10%">Observation</th>
                                    <th style="width:4%">Supp</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($qualites as $qualite)
                                    <tr>
                                        <td style="width:15%;"><input placeholder="Nom"  id='qual-nom-<?php echo $qualite->id;?>'  style="width:100%" value="<?php echo $qualite->nom; ?>" onchange="changingAddress('<?php echo $qualite->id; ?>','nom',this)" /><br><input placeholder="Prenom"   id='qual-prenom-<?php echo $qualite->id;?>'  style="width:100%" value="<?php echo $qualite->prenom; ?>" onchange="changingAddress('<?php echo $qualite->id; ?>','prenom',this)" /></td>
                                        <td style="width:20%;"><input  id='qual-fon-<?php echo $qualite->id;?>'  style="width:100%" value="<?php echo $qualite->fonction; ?>" onchange="changingAddress('<?php echo $qualite->id; ?>','fonction',this)" /></td>
                                        <td style="width:15%;"><input  type="text" pattern="[0-9]" id='qual-tel-<?php echo $qualite->id;?>'  style="width:100%" value="<?php echo $qualite->tel; ?>" onchange="changingAddress('<?php echo $qualite->id; ?>','tel',this)" />    <a  href="#" data-toggle="modal" data-target="#sendsms" onclick="setsms('<?php echo $qualite->tel; ?>')" >SMS <i class="fa fa-sms"></i></a></td>
                                        <td style="width:15%;"><input type="text" pattern="[0-9]" id='qual-fax-<?php echo $qualite->id;?>'  style="width:100%" value="<?php echo $qualite->fax; ?>" onchange="changingAddress('<?php echo $qualite->id; ?>','fax',this)" /></td>
                                        <td style="width:21%;"><input type="email" id='qual-mail-<?php echo $qualite->id;?>'  style="width:100%" value="<?php echo $qualite->mail; ?>" onchange="changingAddress('<?php echo $qualite->id; ?>','mail',this)" /></td>
                                        <td style="width:10%;"><textarea  id='qual-rem-<?php echo $qualite->id;?>'  style="width:100%" onchange="changingAddress('<?php echo $qualite->id; ?>','remarque',this)" ><?php echo $qualite->remarque; ?></textarea></td>
                                        <td style="width:4%;">
                                            <a onclick="return confirm('Êtes-vous sûrs ?')" href="{{action('ClientsController@deleteaddress', $qualite->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                                <span class="fa fa-fw fa-trash-alt"></span>
                                            </a>
                                        </td>
                                     </tr>
                                @endforeach

                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse">
                                    Coordonnées Back Office - Réseau</a>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?php   if ($client->reseau_mail1 !='') { ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 1</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="reseau_mail1" id="reseau_mail1" value="{{ $client->reseau_mail1 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                                    <?php   if ($client->reseau_mail2 !='') { ?>

                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 2</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="reseau_mail2" id="reseau_mail2" value="{{ $client->reseau_mail2 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                            </div>
                            <div class="row">
                                <?php   if ($client->reseau_tel1 !='') { ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 1</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="reseau_tel1" id="reseau_tel1" value="{{ $client->reseau_tel1 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                                    <?php   if ($client->reseau_tel2 !='') { ?>

                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 2</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="reseau_tel2" id="reseau_tel2" value="{{ $client->reseau_tel2 }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                            </div>
                            <div class="row">
                                <?php   if ($client->reseau_fax !='') { ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fax </label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="reseau_fax" id="reseau_fax" value="{{ $client->reseau_fax }}">
                                    </div>
                                </div>
                                    <?php  }  ?>

                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Adresse Postale </label>
                                        <textarea onchange="changing(this)"  type="text" class="form-control" name="adresse_reseau" id="adresse_reseau" value="{{ $client->adresse_reseau }}"> </textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Commentaire  </label>
                                        <textarea onchange="changing(this)"  type="text" class="form-control" name="commentaire_reseau" id="commentaire_reseau" value="{{ $client->commentaire_reseau }}"> </textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="row" style="margin-top:40px">
                                <div class="col-md-8">
                                    <h4><i class="fa fa-lg fa-user"></i>  Responsables Réseau</h4>
                                </div>
                                <div class="col-md-4">
                                    <button style="float:right" id="add6" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding6"><b><i class="fa fa-user"></i> Ajouter un responsable Réseau</b></button>
                                </div>

                            </div>

                            <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                                <thead>
                                <tr class="headtable">
                                    <th style="width:15%">Nom et Prénom</th>
                                    <th style="width:20%">Fonction</th>
                                    <th style="width:15%">Tel</th>
                                    <th style="width:15%">Fax</th>
                                    <th style="width:21%">Email</th>
                                    <th style="width:10%">Observation</th>
                                    <th style="width:4%">Supp</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($reseaux as $reseau)
                                    <tr>
                                        <td style="width:15%;"><input placeholder="Nom"  id='res-nom-<?php echo $reseau->id;?>'  style="width:100%" value="<?php echo $reseau->nom; ?>" onchange="changingAddress('<?php echo $reseau->id; ?>','nom',this)" /><br><input placeholder="Prenom"  id='res-prenom-<?php echo $reseau->id;?>'   style="width:100%" value="<?php echo $reseau->prenom; ?>" onchange="changingAddress('<?php echo $reseau->id; ?>','prenom',this)" /></td>
                                        <td style="width:10%;"><input  id='res-fon-<?php echo $reseau->id;?>'  style="width:100%" value="<?php echo $reseau->fonction; ?>" onchange="changingAddress('<?php echo $reseau->id; ?>','fonction',this)" /></td>
                                        <td style="width:15%;"><input type="text" pattern="[0-9]" id='res-tel-<?php echo $reseau->id;?>'  style="width:100%" value="<?php echo $reseau->tel; ?>" onchange="changingAddress('<?php echo $reseau->id; ?>','tel',this)" />    <a href="#" data-toggle="modal" data-target="#sendsms" onclick="setsms('<?php echo $reseau->tel; ?>')" >SMS <i class="fa fa-sms"></i></a></td>
                                        <td style="width:15%;"><input type="text" pattern="[0-9]" id='res-fax-<?php echo $reseau->id;?>'  style="width:100%" value="<?php echo $reseau->fax; ?>" onchange="changingAddress('<?php echo $reseau->id; ?>','fax',this)" /></td>
                                        <td style="width:21%;"><input  type="email" id='res-mail-<?php echo $reseau->id;?>'  style="width:100%" value="<?php echo $reseau->mail; ?>" onchange="changingAddress('<?php echo $reseau->id; ?>','mail',this)" /></td>
                                        <td style="width:10%;"><textarea  id='res-rem-<?php echo $reseau->id;?>'  style="width:100%" onchange="changingAddress('<?php echo $reseau->id; ?>','remarque',this)"  ><?php echo $reseau->remarque; ?></textarea></td>
                                        <td style="width:4%;">
                                            <a onclick="return confirm('Êtes-vous sûrs ?')" href="{{action('ClientsController@deleteaddress', $reseau->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                                <span class="fa fa-fw fa-trash-alt"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>

        </div>

    @can('isAdmin')
	<?php 
 	$count= \App\Dossier::where('customer_id',$client->id)->count();
	if ($count>0){
		echo 'Suppression interdite : Client impliqué dans '.$count.' dossier(s)'; 
	}else{
		?>
   <a onclick="return confirm('Êtes-vous sûrs ?')" href="{{action('ClientsController@destroy', $client->id )}}" class="pull-right btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
            <span class="fa fa-fw fa-trash-alt"></span> Supprimer le client
        </a>
	<?php 		}
	
	?>
    @endcan

    <input type="hidden" id="idcl" class="form-control"   value={{ $client->id }}>
    <input id="parent" name="parent" type="hidden" value="{{ $client->id}}">


  </div>

  </div>


    <!-- Modal Tels -->
    <div class="modal fade" id="adding1"   role="dialog" aria-labelledby="exampleModal1" aria-hidden="true">
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
                                    <label for="adresse">Téléphone</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="number" required id="champ1"  onchange="checkexiste(this,'tel')" />

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="ville">Type</label>
                                    <div class="row">
                                        <select class="form-control"  id="type1" >
                                            <option value="medical">Médical</option>
                                            <option value="technique">Technique</option>
                                            <option value="commun">Commun</option>
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
    <div class="modal fade" id="adding2"  role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
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
                                    <label for="adresse">Email</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="email" required id="champ2"  onchange="checkexiste(this,'mail')"/>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="ville">Type</label>
                                    <div class="row">
                                        <select class="form-control"  id="type2" >
                                            <option value="medical">Médical</option>
                                            <option value="technique">Technique</option>
                                            <option value="commun">Commun</option>
                                        </select>

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
    <div class="modal fade" id="adding3"   role="dialog" aria-labelledby="exampleModal3" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal3">Ajouter un numéro de Fax </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form     name="">
                                {{ csrf_field() }}

                                <div class="form-group " >
                                    <label for="adresse">Fax</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="number" required id="champ3"  onchange="checkexiste(this,'fax')"/>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="ville">Type</label>
                                    <div class="row">
                                        <select class="form-control"  id="type3" >
                                            <option value="medical">Médical</option>
                                            <option value="technique">Technique</option>
                                            <option value="commun">Commun</option>
                                        </select>

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


    <!-- Modal Adresse de facturation -->
    <div class="modal fade" id="adding4"  role="dialog" aria-labelledby="exampleModal4" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal3">Ajouter une entité à facturer</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form     name="">
                                {{ csrf_field() }}

                                <div class="form-group " >
                                    <label for="adresse">Entité</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="nomadresse"/>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="code">Adresse de facturation</label>
                                    <div class="row">
                                        <textarea   class="form-control"   id="champ4" ></textarea>

                                    </div>
                                </div>
                                <input id="nature4" name="nature" type="hidden" value="facturation">

                            </form>
                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <span type="button" id="btnaddresse" class="btn btn-primary">Ajouter</span>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Qualite -->
    <div class="modal fade" id="adding5"  role="dialog" aria-labelledby="exampleModal5" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal5">Ajouter un Personnel Réseau </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form    name="">
                                {{ csrf_field() }}

                                <div class="form-group " >
                                    <label for="adresse">Nom</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="nomq"/>

                                    </div>
                                </div>
                                <div class="form-group " >
                                    <label for="adresse">Prénom</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="prenomq"/>

                                    </div>
                                </div>

                                <div class="form-group " >
                                    <label for="adresse">Fonction</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="fonctionq"/>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="code">Tel</label>
                                    <div class="row">
                                        <input type="number"   class="form-control"  id="telq"  onchange="checkexiste(this,'tel')" />

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="code">Fax</label>
                                    <div class="row">
                                        <input type="number"   class="form-control"  id="faxq"  onchange="checkexiste(this,'fax')" />

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="code">Email</label>
                                    <div class="row">
                                        <input type="text"   class="form-control"  id="emailq"  onchange="checkexiste(this,'mail')"/>

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="code">Observation</label>
                                    <div class="row">
                                        <textarea   class="form-control"  id="remarqueq" ></textarea>

                                    </div>
                                </div>

                                <input id="nature5" name="nature" type="hidden" value="qualite">



                            </form>
                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <span type="button" id="btnaddqualite" class="btn btn-primary">Ajouter</span>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal Reseau -->
    <div class="modal fade" id="adding6"   role="dialog" aria-labelledby="exampleModal5" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal5">Ajouter un Personnel Rseau </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form    name="">
                                {{ csrf_field() }}

                                <div class="form-group " >
                                    <label for="adresse">Nom</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="nomr"/>

                                    </div>
                                </div>
                                <div class="form-group " >
                                    <label for="adresse">Prénom</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="prenomr"/>

                                    </div>
                                </div>

                                <div class="form-group " >
                                    <label for="adresse">Fonction</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="fonctionr"/>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="code">Tel</label>
                                    <div class="row">
                                        <input type="number"   class="form-control"  id="telr"  onchange="checkexiste(this,'tel')"/>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="code">Fax</label>
                                    <div class="row">
                                        <input type="number"   class="form-control"  id="faxr"  onchange="checkexiste(this,'fax')" />

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="code">Email</label>
                                    <div class="row">
                                        <input type="text"   class="form-control"  id="emailr"  onchange="checkexiste(this,'mail')" />

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="code">Observation</label>
                                    <div class="row">
                                        <textarea   class="form-control"  id="remarquer" ></textarea>

                                    </div>
                                </div>

                                <input id="nature6" name="nature" type="hidden" value="reseau">



                            </form>
                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <span type="button" id="btnaddreseau" class="btn btn-primary">Ajouter</span>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal Reseau -->
    <div class="modal fade" id="adding7"   role="dialog" aria-labelledby="exampleModal7" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal7">Ajouter un Personnel Réseau </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form    name="">
                                {{ csrf_field() }}

                                <div class="form-group " >
                                    <label for="adresse">Nom</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="nomg"/>

                                    </div>
                                </div>
                                <div class="form-group " >
                                    <label for="adresse">Prénom</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="prenomg"/>

                                    </div>
                                </div>

                                <div class="form-group " >
                                    <label for="adresse">Fonction</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="fonctiong"/>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="code">Tel</label>
                                    <div class="row">
                                        <input type="number"   class="form-control"  id="telg"  onchange="checkexiste(this,'tel')" />

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="code">Fax</label>
                                    <div class="row">
                                        <input type="text"   class="form-control"  id="faxg"  onchange="checkexiste(this,'fax')"/>

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="code">Email</label>
                                    <div class="row">
                                        <input type="text"   class="form-control"  id="emailg"  onchange="checkexiste(this,'mail')" />

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="code">Observation</label>
                                    <div class="row">
                                        <textarea   class="form-control"  id="remarqueg" ></textarea>

                                    </div>
                                </div>

                                <input id="nature7" name="nature" type="hidden" value="gestion">



                            </form>
                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <span type="button" id="btnaddgestion" class="btn btn-primary">Ajouter</span>
                </div>
            </div>
        </div>
    </div>






    <!-- Modal Envoi Mail -->
    <div class="modal fade" id="sendmail"   role="dialog" aria-labelledby="exampleModal8" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal7">Envoyer un email au client </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

    <form  enctype="multipart/form-data" id="theform" method="POST" action="{{action('EmailController@send')}}"    onsubmit="return checkForm(this);"  >
        {{ csrf_field() }}

        <input id="dossier" type="hidden" class="form-control" name="dossier"  value="0" />
        <input id="envoye" type="hidden" class="form-control" name="envoye"  value="" />
        <input id="brsaved" type="hidden" class="form-control" name="brsaved"  value="0" />


        <?php
        $from='24ops@najda-assistance.com';

        ?>
        <input type="hidden"   name="from" id="from" value="<?php echo $from; ?>" />


        <div class="row">

            <label for="destinataire">Adresse:</label>
            <div class="row">
                <div class="col-md-10">
                    <input type="email" id="destinataire" required  class="form-control" name="destinataire[]"   />

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
        <div class="form-group">
            <label for="sujet">Sujet :</label>
            <input id="sujet" type="text" class="form-control" name="sujet" required value=" "/>

        </div>


        <div class="form-group">
            <label for="description">Description :</label>
            <input id="description" type="text" class="form-control" name="description" id="description" required/>
        </div>



        <div class="form-group ">
            <label for="contenu">Contenu:</label>
            <div class="editor" >
                <textarea style="min-height: 280px;" id="contenu" type="text"  class="textarea tex-com" placeholder="Contenu de l'email ici" name="contenu" required  ></textarea>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button onclick=" resetForm(this.form);" id="SendBtn" type="submit"  name="myButton" class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
                    </div>
                </div>
            </div>
			</form>
   </div>
   </div>



    <!-- Modal Envoi Mail -->
    <div class="modal fade" id="sendsms"   role="dialog" aria-labelledby="exampleModal8" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal7">Envoyer un SMS au client </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">


    <form method="post" action="{{action('EmailController@sendsmsxml')}}" >
        <input id="dossier" type="hidden" class="form-control" name="dossier"  value="0" />

        <div class="form-group">
            {{ csrf_field() }}
            <label for="description">Description:</label>
            <input id="descriptionsms" type="text" class="form-control" name="description"     />
        </div>


        <div class="form-group">

            <label for="destinataire">Numéro:</label>

            <input id="destinatairesms" type="number" class="form-control" name="destinataire"     />


        </div>

        <div class="form-group">
            <label for="contenu">Message:</label>
            <textarea  type="text" class="form-control" name="message"></textarea>
        </div>


    </form>

     </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button onclick=" resetForm(this.form);" id="SendBtn" type="submit"  name="myButton" class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

 <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>
    function  setmail(email)
    {
        document.getElementById('destinataire').value =''+email;
    }

    function  setsms(num)
    {
        document.getElementById('destinatairesms').value =''+num;
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

           /*     if(data>0){
                    alert('  Existe deja !');
                    document.getElementById(id).style.background='#FD9883';
                    document.getElementById(id).style.color='white';
                } else{
                    document.getElementById(id).style.background='white';
                    document.getElementById(id).style.color='black';
                }
*/
                if(data!=''){
                    parsed = JSON.parse(data);
                    string='Existe deja ! ';
                    if(parsed['nom']!=null){string+='Nom : '+parsed['nom']+ ' - '; }
                    if(parsed['prenom']!=null){string+='Prénom : '+parsed['prenom']+ ' - '; }
                    if(parsed['fonction']!=null){string+='Fonction : '+parsed['fonction']+ ' - '; }
                    if(parsed['remarque']!=null){string+='Remarque : '+parsed['remarque']+ ' - '; }
                    if(parsed['type']!=null){string+='Type : '+parsed['type']+ ' '; }
                    if(parsed['typetel']!=null){string+=parsed['typetel']+ ' '; }
                    if(parsed['nature']=='email' || parsed['nature']=='tel' || parsed['nature']=='fax' )
                    {  // client
                        string+='<br>   lien : <a href="<?php echo $urlapp.'/clients/view/'; ?>'+parsed['parent']+'" target="_blank" >Ouvrir Fiche Client</a>';
                    }else{
                        // intervenant
                        if(parsed['nature']=='emailinterv' || parsed['nature']=='telinterv' || parsed['nature']=='faxinterv' )
                        {  // client
                            string+='<br>   lien : <a href="<?php echo $urlapp.'/prestataires/view/'; ?>'+parsed['parent']+'" target="_blank" >Ouvrir Fiche Prestataire</a>';

                        }
                    }
                   // alert(string);
                    Swal.fire({
                        type: 'error',
                        title: 'Existant...',
                        html: string
                    });

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

    $(function () {

        $('#nature').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });

        $('#docs').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });

        $('#contrats').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });


        $('#btnaddtel').click(function(){
             var parent = $('#parent').val();
            var champ = $('#champ1').val();
            var remarque = $('#remarque1').val();
            var type = $('#type1').val();
            var nature = $('#nature1').val();
            if ((champ != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('clients.addressadd') }}",
                    method:"POST",
                    data:{ parent:parent,champ:champ,remarque:remarque,nature:nature,type:type, _token:_token},
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
             var parent = $('#parent').val();
            var champ = $('#champ2').val();
            var remarque = $('#remarque2').val();
            var type = $('#type2').val();
            var nature = $('#nature2').val();
            if ((champ != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('clients.addressadd') }}",
                    method:"POST",
                    data:{ parent:parent,champ:champ,remarque:remarque,nature:nature,type:type, _token:_token},
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
            var parent = $('#parent').val();
            var champ = $('#champ3').val();
            var remarque = $('#remarque3').val();
            var type = $('#type3').val();
            var nature = $('#nature3').val();
            if ((champ != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('clients.addressadd') }}",
                    method:"POST",
                    data:{parent:parent,champ:champ,remarque:remarque,nature:nature,type:type, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                // alert('ERROR');
            }
        });


        $('#btnaddresse').click(function(){
            var parent = $('#parent').val();
            var nom = $('#nomadresse').val();
            var champ = $('#champ4').val();
             var nature = $('#nature4').val();
            if ((champ != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('clients.addressadd2') }}",
                    method:"POST",
                    data:{parent:parent,champ:champ,nom:nom ,nature:nature, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                // alert('ERROR');
            }
        });




        $('#btnaddqualite').click(function(){
            var parent = $('#parent').val();
            var nom = $('#nomq').val();
            var prenom = $('#prenomq').val();
            var fonction = $('#fonctionq').val();
            var tel = $('#telq').val();
            var email = $('#emailq').val();
            var fax = $('#faxq').val();
            var observ = $('#remarqueq').val();
             var nature = $('#nature5').val();
            if (  (tel!='')  || (email!='')  )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('clients.addressadd3') }}",
                    method:"POST",
                    data:{parent:parent,nom:nom,prenom:prenom,fonction:fonction,tel:tel,fax:fax,email:email,observ: observ, nature:nature, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                 alert("saisir l'email ou le N° Tel");
            }
        });


        $('#btnaddreseau').click(function(){
            var parent = $('#parent').val();
            var nom = $('#nomr').val();
            var prenom = $('#prenomr').val();
            var fonction = $('#fonctionr').val();
            var tel = $('#telr').val();
            var email = $('#emailr').val();
            var fax = $('#faxr').val();
            var observ = $('#remarquer').val();
            var nature = $('#nature6').val();
            if (  (tel!='')  || (email!='')  )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('clients.addressadd3') }}",
                    method:"POST",
                    data:{parent:parent,nom:nom,prenom:prenom,fonction:fonction,tel:tel,fax:fax,email:email,observ: observ, nature:nature, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                alert("saisir l'email ou le N° Tel");
            }
        });



        $('#btnaddgestion').click(function(){
            var parent = $('#parent').val();
            var nom = $('#nomg').val();
            var prenom = $('#prenomg').val();
            var fonction = $('#fonctiong').val();
            var tel = $('#telg').val();
            var email = $('#emailg').val();
            var fax = $('#faxg').val();
            var observ = $('#remarqueg').val();
            var nature = $('#nature7').val();
			
            if (  (tel!='')  || (email!='')  )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('clients.addressadd3') }}",
                    method:"POST",
                    data:{parent:parent,nom:nom,prenom:prenom,fonction:fonction,tel:tel,fax:fax,email:email,observ: observ, nature:nature, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                alert("saisir l'email ou le N° Tel");
            }
        });





        var $gouv = $('#nature');

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


                    var client = $('#idcl').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('clients.updatingnature') }}",
                        method: "POST",
                        data: {client: client ,champ:'nature', val:item ,  _token: _token},
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

                    var client = $('#idcl').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('clients.removenature') }}",
                        method: "POST",
                        data: {client: client ,champ:'nature', val:item ,  _token: _token},
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





        var $topo1 = $('#docs');

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


                    var client = $('#idcl').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('docs.createspec') }}",
                        method: "POST",
                        data: {client: client , doc:item ,  _token: _token},
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

                    var client = $('#idcl').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('docs.removespec') }}",
                        method: "POST",
                        data: {client: client , doc:item ,  _token: _token},
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



        var $topo2 = $('#contrats');

        var valArray0 = ($topo2.val()) ? $topo2.val() : [];

        $topo2.change(function() {
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

                UpdatingC(changes, (numVals > valArray0.length) ? 'selected' : 'removed');

            }else{
                // if change event occurs and previous array length same as new value array : items are removed and added at same time
                UpdatingC( valArray0, 'removed');
                UpdatingC( val0, 'selected');
            }
            valArray0 = (val0) ? val0 : [];
        });



        function UpdatingC(array, type) {
            $.each(array, function(i, item) {

                if (type=="selected"){


                    var parent = $('#idcl').val();
                    var types = 'particulier';
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('contrats.createspec') }}",
                        method: "POST",
                        data: {parent: parent , contrat:item , type:types, _token: _token},
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

                    var parent = $('#idcl').val();
                    var types = 'particulier';
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('contrats.removespec') }}",
                        method: "POST",
                        data: {parent: parent , contrat:item , type:types, _token: _token},
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

    });

    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var client = $('#idcl').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('clients.updating') }}",
            method: "POST",
            data: {client: client , champ:champ ,val:val, _token: _token},
            success: function (data) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });
            }
        });

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
         var client = $('#idcl').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('clients.updating') }}",
            method: "POST",
            data: {client: client , champ:champ ,val:val, _token: _token},
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



</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">

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

	
		
	        $( ".datepicker" ).datepicker({

            altField: "#datepicker",
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            buttonImage: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAATCAYAAAB2pebxAAABGUlEQVQ4jc2UP06EQBjFfyCN3ZR2yxHwBGBCYUIhN1hqGrWj03KsiM3Y7p7AI8CeQI/ATbBgiE+gMlvsS8jM+97jy5s/mQCFszFQAQN1c2AJZzMgA3rqpgcYx5FQDAb4Ah6AFmdfNxp0QAp0OJvMUii2BDDUzS3w7s2KOcGd5+UsRDhbAo+AWfyU4GwnPAYG4XucTYOPt1PkG2SsYTbq2iT2X3ZFkVeeTChyA9wDN5uNi/x62TzaMD5t1DTdy7rsbPfnJNan0i24ejOcHUPOgLM0CSTuyY+pzAH2wFG46jugupw9mZczSORl/BZ4Fq56ArTzPYn5vUA6h/XNVX03DZe0J59Maxsk7iCeBPgWrroB4sA/LiX/R/8DOHhi5y8Apx4AAAAASUVORK5CYII=",

            firstDay: 1,
            dateFormat: "dd-mm-yy"

        });
		
		
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
</script>
<!----- Datepicker ------->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    function modalattachEM(titre,emplacement,type)
    {
        document.getElementById('attachiframeEM').style.display='none';
        document.getElementById('imgattachEM').style.display='none';
        $("#attTitleEM").text(titre);

        if  (type ==  'pdf')
        {
            document.getElementById('attachiframeEM').src =emplacement;
            document.getElementById('attachiframeEM').style.display='block';

            // document.getElementById('attachiframe').src =emplacement;
        }
        if ( (type ==  'doc') || ( type ==  'docx'  ) || ( type ==  'xls'  ) || ( type ==  'xlsx'  )  )
        {document.getElementById('attachiframeEM').src ="https://view.officeapps.live.com/op/view.aspx?src="+emplacement;
            document.getElementById('attachiframeEM').style.display='block';
        }
        if ( (type ==  'png') || (type ==  'jpg') ||(type ==  'jpeg' ) )
        {
            document.getElementById('imgattachEM').style.display='block';
            document.getElementById('imgattachEM').src =emplacement;
            // document.getElementById('attachiframe').src =emplacement;
        }


        // cas DOC fichier DOC
        $("#openattachEM").modal('show');
    }

    var verrou=false;

    $(document).on('click','.select2-selection__choice__remove',function(){

        verrou=true;
        //alert('remove');

    });


    $(document).on('click','.select2-selection__choice',function(){
        var nomf=$(this).attr("title");
        // alert(nomf);

        //var texte = $("#attachs option:selected").text();
        //alert(texte);
        var values = '';
        if(verrou==false)
        {
            $('#attachs option').each(function() {

                //values += $(this).text();

                if($(this).text()==nomf)
                {
                    empl=$(this).attr("pathem");
                    type=$(this).attr("typeem");
                    modalattachEM(nomf,empl,type);
                    return false;
                }


            });
        }
        verrou=false;
        //alert(values);
    })
	

		
</script>
<style>.headtable{background-color: grey!important;color:white;}
    table{margin-bottom:40px;}
    textarea{min-height:80px;}
 </style>

