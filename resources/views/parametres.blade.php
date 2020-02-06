

@extends('layouts.adminlayout')

@section('content')

    <?php

    use App\TypeMission;
    $user = auth()->user();
    $name=$user->name;
    $iduser=$user->id;
    $user_type=$user->user_type;

    $seance =  DB::table('seance')
        ->where('id','=', 1 )->first();
    $disp=$seance->dispatcheur ;

    $supmedic=$seance->superviseurmedic ;
    $suptech=$seance->superviseurtech ;
    $charge=$seance->chargetransport ;
    $disptel=$seance->dispatcheurtel ;
    $disptel2=$seance->dispatcheurtel2 ;
    $disptel3=$seance->dispatcheurtel3 ;
    $veilleur=$seance->veilleur ;

    $debut=$seance->debut ;
    $fin=$seance->fin ;

    $parametres =  DB::table('parametres')
        ->where('id','=', 1 )->first();
    $signature=$parametres->signature ;
    $signature2=$parametres->signature2 ;
    $signature3=$parametres->signature3 ;
    $signature4=$parametres->signature4 ;
    $signature5=$parametres->signature5 ;
    $signature6=$parametres->signature6 ;
    $signature7=$parametres->signature7 ;
    $signature8=$parametres->signature8 ;
    $signature9=$parametres->signature9 ;
    $accuse1=$parametres->accuse1 ;
    $accuse2=$parametres->accuse2 ;
    $dollar_achat=$parametres->dollar_achat ;
    $euro_achat=$parametres->euro_achat ;
    $dollar_vente=$parametres->dollar_vente ;
    $euro_vente=$parametres->euro_vente ;
    $pass_Fax=$parametres->pass_Fax ;
    $pass_VAT=$parametres->pass_VAT ;
    $pass_MEDIC=$parametres->pass_MEDIC ;
    $pass_TM=$parametres->pass_TM ;
    $pass_TV=$parametres->pass_TV ;

    $pass_N=$parametres->pass_N ;
    $pass_TN=$parametres->pass_TN ;
    $pass_TPA=$parametres->pass_TPA ;
    $pass_XP=$parametres->pass_XP ;
    $pass_MI=$parametres->pass_MI ;



    ?>

                    <h3 class="card-title">Tableau de bord</h3><br>
                         <!-- Tabs -->
                        <ul class="nav  nav-tabs">
                            <?php if($user_type=='admin') { ?>

                            <li class="nav-item active">
                                <a class="nav-link  <?php if ($user_type=='admin'){echo 'active';}?> " href="#tab1" data-toggle="tab" onclick="showinfos();hideinfos2();hideinfos3();;hideinfos4()">
                                    <i class="fa-lg fas fa-user-clock"></i>  Séance
                                </a>
                            </li>
                           <li class="nav-item">
                                <a class="nav-link" href="#tab2" data-toggle="tab" onclick="showinfos2();hideinfos();hideinfos3();hideinfos4()">
                                    <i class="fa-lg fas fa-sliders-h"></i>  Paramètres
                                </a>
                            </li>
                            <?php }

                            if( ($user_type=='superviseur')  || ( ($user_type=='admin')) ) {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($user_type=='superviseur'){echo 'active';}?>" href="#tab3" data-toggle="tab" onclick="showinfos3();hideinfos();hideinfos2();hideinfos4()">
                                    <i class="fa-lg fas fa-users"></i>  Supervision
                                </a>
                            </li>
                         <?php  } if( ($user_type=='financier')  || ( ($user_type=='admin')) ) {

                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($user_type=='financier'){echo 'active';}?>" href="#tab4" data-toggle="tab" onclick="showinfos4();hideinfos();hideinfos2();hideinfos3();hideinfos5()">
                                    <i class="fa-lg fas fa-users"></i>  Finances
                                </a>
                            </li>
                                <?php if($user_type=='admin') { ?>
                                <li class="nav-item">
                                    <a class="nav-link " href="#tab5" data-toggle="tab" onclick="showinfos5();hideinfos();hideinfos2();hideinfos4();hideinfos3()">
                                        <i class="fa-lg fas fa-gears"></i>  Paramètres Type Missions
                                    </a>
                                </li>
                                <?php }?>

                            <?php }?>
                        </ul>

<?php if($user_type=='admin') { ?>
                <div id="tab1" class="tab-pane fade <?php if ($user_type=='admin'){echo 'active in';}?> ">
                   <div class="padding:50px 50px 50px 50px">
                       <br>
                   <h4>Séance  </h4><br>
                            <?php

                       // ChampById
                          ?>
                            <table class="table" style="width:80%">

                                <form class="form-horizontal" method="POST"></form>

                                {{ csrf_field() }}
                                <tbody>
                                <tr>
                                    <td  style="width:30%" class="text-primary">Début de la séance du jour</td>
                                    <td  style="width:70%">
                                            <input id="debut" onchange="changingseance(this)" type="text" class="form-control" name="debut" value="<?php echo $debut; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Fin de la séance du jour</td>
                                    <td>
                                        <input id="fin" onchange="changingseance(this)" type="text" class="form-control" name="fin" value="<?php echo $fin; ?>">
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-primary">Superviseur Médical</td>
                                    <td>
                                        <select  onchange="changingseance(this)"  id="superviseurmedic" name="superviseurmedic"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                        @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$supmedic)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>                                       </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Superviseur Technique</td>
                                    <td>
                                        <select  onchange="changingseance(this)" id="superviseurtech" name="superviseurtech"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                        @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$suptech)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>                                       </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Dispatcheur</td>
                                    <td>
                                        <select  onchange="changingseance(this)"  id="dispatcheur" name="dispatcheur"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                            @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$disp)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Dispatcheur Téléphonique 1</td>
                                    <td>
                                        <select onchange="changingseance(this)"  id="dispatcheurtel" name="dispatcheurtel"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                            @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$disptel)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Dispatcheur Téléphonique 2</td>
                                    <td>
                                        <select onchange="changingseance(this)"  id="dispatcheurtel2" name="dispatcheurtel2"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                            @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$disptel2)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Dispatcheur Téléphonique 3</td>
                                    <td>
                                        <select onchange="changingseance(this)"  id="dispatcheurtel3" name="dispatcheurtel3"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                            @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$disptel3)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Chargé de transport</td>
                                    <td>
                                        <select onchange="changingseance(this)"  id="chargetransport" name="chargetransport"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                            @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$charge)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            <?php     $date_actu =date("H:i");
                            $date_actu=strtotime($date_actu);
                          $debut= strtotime($debut);
                          $fin= strtotime($fin);
              
                            if ( ($date_actu < $debut )|| ($date_actu > $fin ) )
                                {  ?>
                                <tr>
                                    <td class="text-primary">Veilleur de nuit</td>
                                    <td>
                                        <select onchange="changingseance(this)"   id="veilleur" name="veilleur"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                        @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$veilleur)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                              <?php  } ?>
                                </tbody>
                            </table>

                       <H2>Agents Enregistrés Connectés :</H2><br>

                       <?php
                       foreach($users as $user)
                       {
                           if($user->statut == 1 ){

                               $role=' ';
                               if($user->id==$veilleur){$role.='(Veilleur de nuit)';}
                               if($user->id==$disp){$role.='(Dispatcheur)';}
                               if($user->id==$disptel){$role.='(Dispatcheur Téléphonique)';}
                               if($user->id==$disptel2){$role.='(Dispatcheur Téléphonique 2)';}
                               if($user->id==$disptel3){$role.='(Dispatcheur Téléphonique 3)';}
                               if($user->id==$supmedic){$role.='(Superviseur Médical)';}
                               if($user->id==$suptech){$role.='(Superviseur Technique)';}
                               if($user->id==$charge){$role.='(Chargé de transport)';}
                                if($user->user_type!='admin') { echo  '<li style="margin-bottom:20px"><i class="fa fa-user fa-lg" ></i><span  style="min-width:300px">   '.$user->name.' '.$user->lastname .' - '. $role.' </span>'; if(! $user->isOnline() ){ echo ' <b>Non Actif</b> <button class="btn btn-danger" onclick="deconnecter('.$user->id.')" > Déconnecter </button>';} echo '</li>  ' ;}

                           }


                       }?>

                        </div>
                     </div>

                   <div id="tab2" class="tab-pane fade " style="display:block">
                        <div class="padding:50px 50px 50px 50px"><br>
                        <h4>Paramètres</h4><br>

                            <table class="table">

                                <form class="form-horizontal" method="POST"></form>

                                {{ csrf_field() }}
                                <tbody>

                                 <tr>
                                    <td style="width:35%" class="text-primary">Signature Najda</td>
                                    <td style="width:65%">
                                        <textarea  class="form-control" onchange="changing(this)" id="signature" style="width:520px;height:100px"><?php echo $signature; ?></textarea>
                                    </td>
                                </tr>
                                 <tr>
                                     <td style="width:35%" class="text-primary">Signature VAT</td>
                                     <td style="width:65%">
                                         <textarea  class="form-control" onchange="changing(this)" id="signature2" style="width:520px;height:100px"><?php echo $signature2; ?></textarea>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td style="width:35%" class="text-primary">Signature MEDIC</td>
                                     <td style="width:65%">
                                         <textarea  class="form-control" onchange="changing(this)" id="signature3" style="width:520px;height:100px"><?php echo $signature3; ?></textarea>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td style="width:35%" class="text-primary">Signature Transport MEDIC</td>
                                     <td style="width:65%">
                                         <textarea  class="form-control" onchange="changing(this)" id="signature4" style="width:520px;height:100px"><?php echo $signature4; ?></textarea>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td style="width:35%" class="text-primary">Signature Transport VAT</td>
                                     <td style="width:65%">
                                         <textarea  class="form-control" onchange="changing(this)" id="signature5" style="width:520px;height:100px"><?php echo $signature5; ?></textarea>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td style="width:35%" class="text-primary">Signature Medic International</td>
                                     <td style="width:65%">
                                         <textarea  class="form-control" onchange="changing(this)" id="signature6" style="width:520px;height:100px"><?php echo $signature6; ?></textarea>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td style="width:35%" class="text-primary">Signature Najda TPA</td>
                                     <td style="width:65%">
                                         <textarea  class="form-control" onchange="changing(this)" id="signature7" style="width:520px;height:100px"><?php echo $signature7; ?></textarea>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td style="width:35%" class="text-primary">Signature Transport Najda</td>
                                     <td style="width:65%">
                                         <textarea  class="form-control" onchange="changing(this)" id="signature8" style="width:520px;height:100px"><?php echo $signature8; ?></textarea>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td style="width:35%" class="text-primary">Signature X-Press</td>
                                     <td style="width:65%">
                                         <textarea  class="form-control" onchange="changing(this)" id="signature9" style="width:520px;height:100px"><?php echo $signature9; ?></textarea>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td class="text-primary">Mot de Passe boite Fax <small>najdassist@gmail.com</small></td>
                                     <td>
                                         <input  type="text" class="form-control" onchange="changing(this)" id="pass_Fax" style="width:300px" value="<?php echo $pass_Fax; ?>"  />
                                     </td>
                                 </tr>
                                 <tr>
                                     <td class="text-primary">Mot de Passe boite N <small>24ops@najda-assistance.com</small></td>
                                     <td>
                                         <input  type="text" class="form-control" onchange="changing(this)" id="pass_N" style="width:300px" value="<?php echo $pass_N; ?>"  />
                                     </td>
                                 </tr>
                                 <tr>
                                     <td class="text-primary">Mot de Passe boite TN <small>taxi@najda-assistance.com</small></td>
                                     <td>
                                         <input  type="text" class="form-control" onchange="changing(this)" id="pass_TN" style="width:300px" value="<?php echo $pass_TN; ?>"  />
                                     </td>
                                 </tr>
                                 <tr>
                                     <td class="text-primary">Mot de Passe boite TPA <small>tpa@najda-assistance.com</small></td>
                                     <td>
                                         <input  type="text" class="form-control" onchange="changing(this)" id="pass_TPA" style="width:300px" value="<?php echo $pass_TPA; ?>"  />
                                     </td>
                                 </tr>
                                 <tr>
                                     <td class="text-primary">Mot de Passe boite XP <small>x-press@najda-assistance.com</small></td>
                                     <td>
                                         <input  type="text" class="form-control" onchange="changing(this)" id="pass_XP" style="width:300px" value="<?php echo $pass_XP; ?>"  />
                                     </td>
                                 </tr>

                                 <tr>
                                     <td class="text-primary">Mot de Passe boite VAT <small>hotels.vat@medicmultiservices.com</small></td>
                                           <td>
                                               <input  type="text" class="form-control" onchange="changing(this)" id="pass_VAT" style="width:300px"  value="<?php echo $pass_VAT; ?>"  />
                                           </td>
                                       </tr>
                                       <tr>
                                           <td class="text-primary">Mot de Passe boite MEDIC <small>assistance@medicmultiservices.com</small></td>
                                           <td>
                                               <input  type="text" class="form-control" onchange="changing(this)" id="pass_MEDIC" style="width:300px"  value="<?php echo $pass_MEDIC; ?>"  />
                                           </td>
                                       </tr>
                                       <tr>
                                           <td class="text-primary">Mot de Passe boite TM <small>ambulance.transp@medicmultiservices.com</small></td>
                                           <td>
                                               <input  type="text" class="form-control" onchange="changing(this)" id="pass_TM" style="width:300px"  value="<?php echo $pass_TM; ?>"  />
                                           </td>
                                       </tr>
                                       <tr>
                                           <td class="text-primary">Mot de Passe boite TV <small>vat.transp@medicmultiservices.com</small></td>
                                           <td>
                                               <input  type="text" class="form-control" onchange="changing(this)" id="pass_TV" style="width:300px" value="<?php echo $pass_TV; ?>"  />
                                           </td>
                                       </tr>
                                       <tr>
                                           <td class="text-primary">Mot de Passe boite MI <small>operations@medicinternational.tn</small></td>
                                           <td>
                                               <input  type="text" class="form-control" onchange="changing(this)" id="pass_MI" style="width:300px" value="<?php echo $pass_MI; ?>"  />
                                           </td>
                                       </tr>

                                       <tr>
                                           <td class="text-primary">Mail Semi Auto - Création de dossier (Fr)</td>
                                           <td>
                                               <textarea  class="form-control" onchange="changing(this)" id="accuse1" style="width:520px;height:350px"><?php echo $accuse1 ; ?></textarea>
                                           </td>
                                       </tr>
                                       <tr>
                                           <td class="text-primary">Mail Semi Auto - Création de dossier (Eng)</td>
                                           <td>
                                               <textarea  class="form-control" onchange="changing(this)" id="accuse2" style="width:520px;height:350px"><?php echo $accuse2; ?></textarea>
                                           </td>
                                       </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
<?php }

if( ($user_type=='superviseur')  || ( ($user_type=='admin')) ) {
?>
                    <div id="tab3" class="tab-pane fade <?php if ($user_type=='superviseur'){echo 'in active';}?>" style="display:block">
                        <div class="padding:50px 50px 50px 50px"><br>
                            <h4>Agents Actifs</h4><br>
                            <ul style="width:80%;background-color:#F8F7F6;padding:50px 50px 50px 50px">

                            <?php
                            foreach($users as $user)
                                {
                                    $role=' ';
                                    if($user->id==$veilleur){$role.='(Veilleur de nuit)';}
                                    if($user->id==$disp){$role.='(Dispatcheur)';}
                                    if($user->id==$disptel){$role.='(Dispatcheur Téléphonique)';}
                                    if($user->id==$disptel2){$role.='(Dispatcheur Téléphonique 2)';}
                                    if($user->id==$disptel3){$role.='(Dispatcheur Téléphonique 3)';}
                                    if($user->id==$supmedic){$role.='(Superviseur Médical)';}
                                    if($user->id==$suptech){$role.='(Superviseur Technique)';}
                                    if($user->id==$charge){$role.='(Chargé de transport)';}
                                    if($user->user_type=='admin'){$role.='(Administrateur)';}
                                  if($user->isOnline()  && $user->statut!= -1 ) { echo  '<li><i class="fa fa-user fa-lg" ></i>   '.$user->name.' '.$user->lastname .' - '. $role.' </li>' ;}
                                }
                                    ?><br>

                            </ul>
                        </div>
                    </div>
    <?php  }
?>

<div id="tab5" class="tab-pane fade   " style="display:block">
<div class="padding:20px 20px 20px 20px"><br>

    <?php
    $type_missions = TypeMission::get();


    ?>

    <div class="row">
        <div class="col-md-1" style="width:170px;margin-bottom:10px;">
       Type de Mission:
        </div>
        <div class="col-md-6">

        <select  onchange="LoadTypeM()" id="typemission" class="form control select2"   style="width:650px" >
        <option></option>
        <?php
        foreach($type_missions as $tm)
        { ?>
        <option value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
    </select>
        </div>
   </div>
    <div id="data" >


    </div>

    </div>
    </div><!--  Tab 5  -->

 <?php   if( ($user_type=='financier')  || ( ($user_type=='admin')) ) {

    ?>
                    <div id="tab4" class="tab-pane fade <?php if ($user_type=='financier'){echo 'in active';}?> " style="display:block">
                        <div class="padding:10px 20px 20px 20px"><br>
                            <h4>Paramètres finances</h4><br>
                            <table class="table" style="width:600px">
                                <form class="form-horizontal" method="POST"></form>
                                {{ csrf_field() }}
                                <tbody>
                                <tr>
                                    <td class="text-primary">1 Euro en dinars (Achat)</td>
                                    <td>
                                        <input class="form-control" onchange="changing(this)" type="number" step="0.01" id="euro_achat"  style="width:100px" value="<?php echo $euro_achat; ?>" /> (TND)
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">1 Dollar Américain en dinars (Achat)</td>
                                    <td>
                                        <input  class="form-control" onchange="changing(this)" type="number" step="0.01"  id="dollar_achat"  style="width:100px" value="<?php echo $dollar_achat; ?>" /> (TND)
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">1 Euro en dinars (Vente)</td>
                                    <td>
                                        <input class="form-control" onchange="changing(this)" type="number" step="0.01" id="euro_vente"  style="width:100px" value="<?php echo $euro_vente; ?>" /> (TND)
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">1 Dollar Américain en dinars (Vente)</td>
                                    <td>
                                        <input  class="form-control" onchange="changing(this)" type="number" step="0.01"  id="dollar_vente"  style="width:100px" value="<?php echo $dollar_vente; ?>" /> (TND)
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

    <?php } ?>


    <!-- Modal   -->
    <div class="modal fade" id="act_description" role="dialog" aria-labelledby="description" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModal7" style="text-align: center">Description de l'action </h2>
                </div>
                <form   >
                    <div class="modal-body" style="padding:40px 20px 20px 20px">
                        <div class="card-body">

                            <div class="form-group">
                                <input type="hidden" id="selectedaction" />
                                {{ csrf_field() }}
                                <label for="description">Description:</label>
                                <textarea onchange="updateDescAct()" style="width:100%;height:400px;" id="descrip_act"  class="form-control" name="description"     ></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-left:30px">Fermer</button>

                    </div>
                </form>

            </div>
        </div>
    </div>

 @endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>

    function LoadTypeM() {

         var select = document.getElementById("typemission");
        var typemission = select.options[select.selectedIndex].value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('typesmissions.loading') }}",
            method: "post",

            data: {typemission: typemission,  _token: _token},
            success: function (data) {

                $('#data').html(data);
            }
        }); // ajax

    }


  //   $("#typemission").select2();

     function hideinfos() {
        $('#tab1').css('display','none');
    }
    function hideinfos2() {
        $('#tab2').css('display','none');
    }
    function hideinfos3() {
        $('#tab3').css('display','none');
    }
    function hideinfos4() {
        $('#tab4').css('display','none');
    }
    function showinfos() {
        $('#tab1').css('display','block');
    }
    function showinfos2() {
        $('#tab2').css('display','block');
    }
    function showinfos3() {
        $('#tab3').css('display','block');
    }
    function showinfos4() {
        $('#tab4').css('display','block');
    }
    function showinfos5() {
        $('#tab5').css('display','block');
    }

    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('home.parametring') }}",
            method: "POST",
            data: {  champ:champ ,val:val, _token: _token},
            success: function ( ) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });

    }


    function changingseance(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('home.parametring2') }}",
            method: "POST",
            data: {  champ:champ ,val:val, _token: _token},
            success: function ( ) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });

    }
    function updateDesc(elm) {
        var champ=elm.id;
        var typemission= champ.slice(5);
         var description =document.getElementById(champ).value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('typesmissions.updatedesc') }}",
            method: "POST",
            data: {  typemission:typemission ,description:description, _token: _token},
            success: function ( ) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });

    }

    function updateDescAct( ) {
        var typemission  =document.getElementById('typemission').value;
        var description =document.getElementById('descrip_act').value;
       var action= document.getElementById('selectedaction').value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('typesmissions.updatedescact') }}",
            method: "POST",
            data: {  typemission:typemission ,action:action,description:description, _token: _token},
            success: function ( ) {
                $('#descrip_act').animate({
                    opacity: '0.3',
                });
                $('#descrip_act').animate({
                    opacity: '1',
                });

            }
        });

    }


    function updateCharge(elm) {
        var champ=elm.id;
        var charge=document.getElementById(champ).value;
        var typemission  =document.getElementById('typemission').value;
        var action=champ.slice(7);

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('typesmissions.updatecharge') }}",
            method: "POST",
            data: {  typemission:typemission ,action:action, charge:charge, _token: _token},
            success: function ( ) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });

    }


    function deconnecter(user) {

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('home.deconnecter') }}",
            method: "POST",
            data: {  user:user  , _token: _token},
            success: function ( ) {
                 location.reload();
            }
        });

    }


    function ShowModal(elm)
    {
        var champ=elm.id;
        var action=champ.slice(4);
 document.getElementById('selectedaction').value=action;
 description=document.getElementById(champ).title;
document.getElementById('descrip_act').value=description;
         $('#act_description').modal({show:true});

    }


</script>

<style>
    .mytable{padding:20px 20px 20px 20px;border: 1px solid #00aced;margin-bottom: 50px;}
    .mytable th {background-color: #00aced;color:white;font-weight: 600;text-align: center;height:40px;}
    .mytable td {text-align: center;height:35px;}
    .mytable input{text-align: center;}
</style>

