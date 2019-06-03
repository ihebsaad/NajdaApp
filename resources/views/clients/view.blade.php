@extends('layouts.mainlayout')

@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>



    <div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">

    <div id="addclientform">
        <div class="portlet box grey">
            <div class="modal-header">Client</div>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Nom *</label>
                        <input onchange="changing(this)"  type="text" class="form-control input" name="name" id="name"   value="{{ $client->name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Référence *</label>
                        <input onchange="changing(this)"  type="text" class="form-control input" name="reference" id="reference"   value="{{ $client->reference }}">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
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
                <div class="col-md-6">
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
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Langue 1</label>
                        <select onchange="changing(this)"  class="form-control" name="langue1" id="langue1"  value="{{ $client->langue1 }}">
                            <option <?php if ($client->langue1 =='0'){echo 'selected="selected"';} ?> value="0"></option>
                            <option <?php if ($client->langue1 =='francais'){echo 'selected="selected"';} ?>value="francais">Français</option>
                            <option <?php if ($client->langue1 =='anglais'){echo 'selected="selected"';} ?>  value="anglais">Anglais</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
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
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Nature</label>

                        <select   class="form-control select2-offscreen" name="nature[]" id="nature" multiple="" tabindex="-1">
                            <option <?php   if(strpos($client->nature ,'1')!==false) {echo 'selected="selected"';} ?> value="1">Assistance / Assurance</option>
                            <option <?php   if(strpos($client->nature ,'2')!==false) {echo 'selected="selected"';} ?>value="2">Avionneur</option>
                            <option <?php   if(strpos($client->nature ,'3')!==false) {echo 'selected="selected"';} ?>value="3">Pétrolier / apparenté</option>
                            <option <?php   if(strpos($client->nature ,'4')!==false) {echo 'selected="selected"';} ?>value="4">Clinique</option>
                            <option <?php   if(strpos($client->nature ,'5')!==false) {echo 'selected="selected"';} ?>value="5">Agence de voyage / Hôtel</option>
                        </select>

                </div>
                </div>

                <div class="col-md-5">
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
                        <label for="inputError" class="control-label">Email 10</label> <input onchange="changing(this)"  type="text" id="email10" class="form-control" name="email10"  value="{{ $client->mail10 }}">

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
                                    <button style="float:right" id="add1" class="btn btn-md btn-success"   data-toggle="modal" data-target="#adding1"><b><i class="fa fa-phone"></i> Ajouter un numéro Tel</b></button>
                                </div>

                            </div>
                            <table class="table table-striped"  style="width:100%;margin-top:35px;margin-bottom:35px;font-size:16px;">
                                <thead>
                                <tr id="headtable">
                                    <th style="width:20%">Tel</th>
                                    <th style="width:20%">Type</th>
                                    <th style="width:50%">Remarque</th>
                                    <th style="width:10%">Contacter</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($tels as $tel)
                                    <tr>
                                        <td style="width:20%;"><?php echo $tel->champ; ?></td>
                                        <td style="width:20%;"><?php echo $tel->type; ?></td>
                                        <td style="width:50%;"><?php echo $tel->remarque; ?></td>
                                        <td style="width:10%;"><i class="fa fa-phone"></i></td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>


                            <div class="row" style="margin-top:30px">
                                <div class="col-md-8">
                                    <h4><i class="fa fa-lg fa-envelope"></i>  Adresses Emails </h4>
                                </div>
                                <div class="col-md-4">
                                    <button style="float:right" id="add2" class="btn btn-md btn-success"   data-toggle="modal" data-target="#adding2"><b><i class="fa fa-envelope"></i> Ajouter une adresse email</b></button>
                                </div>

                            </div>
                            <table class="table table-striped"  style="width:100%;margin-top:35px;margin-bottom:35px;font-size:16px;">
                                <thead>
                                <tr id="headtable">
                                    <th style="width:20%">Email</th>
                                    <th style="width:20%">Type</th>
                                    <th style="width:50%">Remarque</th>
                                    <th style="width:10%">Contacter</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($emails as $email)
                                    <tr>
                                        <td style="width:20%;"><?php echo $email->champ; ?></td>
                                        <td style="width:20%;"><?php echo $email->type; ?></td>
                                        <td style="width:50%;"><?php echo $email->remarque; ?></td>
                                        <td style="width:10%;"><i class="fa fa-envelope"></i></td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                            <div class="row" style="margin-top:30px">
                                <div class="col-md-8">
                                    <h4><i class="fa fa-lg fa-fax"></i>  Numéros de Fax </h4>
                                </div>
                                <div class="col-md-4">
                                    <button style="float:right" id="add3" class="btn btn-md btn-success"   data-toggle="modal" data-target="#adding3"><b><i class="fa fa-fax"></i> Ajouter un numéro de fax</b></button>
                                </div>

                            </div>

                            <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                                <thead>
                                <tr id="headtable">
                                    <th style="width:20%">Tel</th>
                                    <th style="width:20%">Type</th>
                                    <th style="width:50%">Remarque</th>
                                    <th style="width:10%">Contacter</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($faxs as $fax)
                                    <tr>
                                        <td style="width:20%;"><?php echo $fax->champ; ?></td>
                                        <td style="width:20%;"><?php echo $fax->type; ?></td>
                                        <td style="width:50%;"><?php echo $fax->remarque; ?></td>
                                        <td style="width:10%;"><i class="fa fa-fax"></i></td>
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
                                        <input onchange="changing(this)" type="text" class="form-control" name="entite" id="entite"  value="{{ $client->entite }}">
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
                                    <h4>Entités de facturations différentes</h4>
                                </div>
                                <div class="col-md-4">
                                    <button style="float:right" id="add4" class="btn btn-md btn-success"   data-toggle="modal" data-target="#adding4"><b><i class="fa fa-map-marker"></i> Ajouter une adresse de facturation</b></button>
                                </div>

                            </div>

                            <table class="table table-striped"  style="width:100%;margin-top:15px;font-size:16px;">
                                <thead>
                                <tr id="headtable">
                                    <th style="width:30%">Entité de facturation</th>
                                    <th style="width:70%">Adresse Posale complète</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($entites as $entite)
                                    <tr>
                                        <td style="width:30%;"><?php echo $entite->nom; ?></td>
                                        <td style="width:70%;"><?php echo $entite->champ; ?></td>
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


                            <div class="row" style="margin-top:30px">
                                <div class="col-md-8">
                                    <h4><i class="fa fa-lg fa-user"></i>  Personnels Qualité</h4>
                                </div>
                                <div class="col-md-4">
                                    <button style="float:right" id="add5" class="btn btn-md btn-success"   data-toggle="modal" data-target="#adding5"><b><i class="fa fa-user"></i> Ajouter un personnel Qualité</b></button>
                                </div>

                            </div>

                            <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                                <thead>
                                <tr id="headtable">
                                    <th style="width:20%">Nom et Prénom</th>
                                    <th style="width:20%">Fonction</th>
                                    <th style="width:50%">Tel</th>
                                    <th style="width:10%">Fax</th>
                                    <th style="width:10%">Email</th>
                                    <th style="width:10%">Observation</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($qualites as $qualite)
                                    <tr>
                                        <td style="width:20%;"><?php echo $qualite->nom; ?>  <?php echo $qualite->prenom; ?></td>
                                        <td style="width:20%;"><?php echo $qualite->fonction; ?></td>
                                        <td style="width:50%;"><?php echo $qualite->tel; ?></td>
                                        <td style="width:50%;"><?php echo $qualite->fax; ?></td>
                                        <td style="width:50%;"><?php echo $qualite->mail; ?></td>
                                        <td style="width:50%;"><?php echo $qualite->remarque; ?></td>
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


                            <div class="row" style="margin-top:30px">
                                <div class="col-md-8">
                                    <h4><i class="fa fa-lg fa-user"></i>  Personnels Réseau</h4>
                                </div>
                                <div class="col-md-4">
                                    <button style="float:right" id="add6" class="btn btn-md btn-success"   data-toggle="modal" data-target="#adding6"><b><i class="fa fa-user"></i> Ajouter un personnel Réseau</b></button>
                                </div>

                            </div>

                            <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                                <thead>
                                <tr id="headtable">
                                    <th style="width:20%">Nom et Prénom</th>
                                    <th style="width:20%">Fonction</th>
                                    <th style="width:50%">Tel</th>
                                    <th style="width:10%">Fax</th>
                                    <th style="width:10%">Email</th>
                                    <th style="width:10%">Observation</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($reseaux as $reseau)
                                    <tr>
                                        <td style="width:20%;"><?php echo $reseau->nom; ?>  <?php echo $reseau->prenom; ?></td>
                                        <td style="width:20%;"><?php echo $reseau->fonction; ?></td>
                                        <td style="width:50%;"><?php echo $reseau->tel; ?></td>
                                        <td style="width:50%;"><?php echo $reseau->fax; ?></td>
                                        <td style="width:50%;"><?php echo $reseau->mail; ?></td>
                                        <td style="width:50%;"><?php echo $reseau->remarque; ?></td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>










                        </div>
                    </div>
                </div>
            </div>

        </div>


    <input type="hidden" id="idcl" class="form-control"   value={{ $client->id }}>
    <input id="parent" name="parent" type="hidden" value="{{ $client->id}}">


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
                                    <label for="adresse">Téléphone</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="champ1"/>

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
                                    <label for="adresse">Email</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="champ2"/>

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
                                    <label for="adresse">Fax</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="text" required id="champ3"/>

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
    <div class="modal fade" id="adding4" tabindex="-1" role="dialog" aria-labelledby="exampleModal4" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal3">Ajouter une adresse de facturation </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form   id="fggf" name="">
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
    <div class="modal fade" id="adding5" tabindex="-1" role="dialog" aria-labelledby="exampleModal5" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal5">Ajouter un Personnel Réseau </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form   id="fggf" name="">
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
                                        <input type="text"   class="form-control"  id="telq" />

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="code">Fax</label>
                                    <div class="row">
                                        <input type="text"   class="form-control"  id="faxq" />

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="code">Email</label>
                                    <div class="row">
                                        <input type="text"   class="form-control"  id="emailq" />

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
    <div class="modal fade" id="adding6" tabindex="-1" role="dialog" aria-labelledby="exampleModal5" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal5">Ajouter un Personnel Rseau </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">

                            <form   id="fggf" name="">
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
                                        <input type="text"   class="form-control"  id="telr" />

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="code">Fax</label>
                                    <div class="row">
                                        <input type="text"   class="form-control"  id="faxr" />

                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="code">Email</label>
                                    <div class="row">
                                        <input type="text"   class="form-control"  id="emailr" />

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

@endsection

 <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>

    $(function () {

        $('#nature').select2({
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
            if ((nom != '') )
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
                // alert('ERROR');
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
            if ((nom != '') )
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
                // alert('ERROR');
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
