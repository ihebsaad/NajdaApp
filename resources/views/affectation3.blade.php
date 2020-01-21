<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src='https://bevacqua.github.io/dragula/dist/dragula.js'></script>

<!--
<html>
<head>
</head>
<body>
<div class="parent">
    <div class="wrapper">
        <div id="drag-elements" class="container">
            <div>Something 1</div>
            <div>Something 2</div>
            <div>Something 3</div>
            <div>Something 4</div>
            <div>Something 5</div>
            <div>Something 6</div>
            <div>Something 7</div>
            <div>Something 8</div>
            <div>Something 9</div>
        </div>
        <div id="drag-elements2" class="container">
            <div>Something A</div>
            <div>Something B</div>
            <div>Something C</div>
            <div>Something D</div>
            <div>Something E</div>
            <div>Something F</div>
            <div>Something G</div>
            <div>Something H</div>
            <div>Something I</div>
        </div>
    </div>
</div>
</body>
</html>
-->


@extends('layouts.supervislayout')

@section('content')

    <div id="mainc" class=" row" style="padding:30px 30px 30px 30px  ">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif

        @if (!empty( Session::get('success') ))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>

        @endif

        <div class="panel panel-primary column col-md-6"  style="margin-left:30px;margin-right:50px;padding:0;min-height: 800px" >
            <div class="panel-heading">
                <h4 id="kbspaneltitle" class="panel-title">Agents connectés  </h4>

            </div>

            <div class="panel-body scrollable-panel" style="display: block;min-height:1000px;padding:15px 15px 15px 15px">
                <ul id="tabs" class="nav  nav-tabs"  >
                    <li class=" nav-item ">
                        <a class="nav-link active   " href="{{ route('supervision') }}"  >
                            <i class="fas fa-lg  fa-users-cog"></i>  Supervision
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('affectation') }}"  >
                            <i class="fas fa-lg  fa-user-tag"></i>  Affectations
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('missions') }}"  >
                            <i class="fas fa-lg  fa-user-cog"></i>  Missions
                        </a>
                    </li>

                    <li class="nav-item ">
                        <a class="nav-link " href="{{ route('notifs') }}"  >
                            <i class="fa fa-lg  fa-inbox"></i>  Flux de réception
                        </a>
                    </li>

                </ul>
                <?php
                use \App\Http\Controllers\UsersController;
                use \App\Http\Controllers\ClientsController;
                $user = auth()->user();
                $iduser=$user->id;
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


                function custom_echo($x, $length)
                {
                    if(strlen($x)<=$length)
                    {
                        echo $x;
                    }
                    else
                    {
                        $y=substr($x,0,$length) . '..';
                        echo $y;
                    }
                }

                use \App\Dossier;
                /*
             $dossiers = Dossier:://where('affecte','=','0')
                          whereNull('affecte')
                          ->orWhere('affecte',0)
                  //->where('affecte','<', 1)  ajouter conditions non affectes
                          ->get();
            */
                // changer condition :
                // dossier par type supervision medic /..
                //+ Actif et non affectés

                if($iduser==$suptech)
                {

                }

                if($iduser==$supmedic)
                {

                }

                $dossiers=    Dossier::where('current_status','actif')
                    ->where(function ($query) {
                        $query->whereNull('affecte')
                            ->orWhere('affecte', 0);

                    })->get();

                $Cdossiers=    Dossier::where('current_status','actif')
                    ->where(function ($query) {
                        $query->whereNull('affecte')
                            ->orWhere('affecte', 0);

                    })->count();

                //  ajouter références dossiers ici *********


                // sup medic
                /*  $dossiersSM=    Dossier::where('affecte',$supmedic)
                               ->where('statut',2)
                                    ->get();

                           $CdossiersSM=    Dossier::where('affecte',$supmedic)
                               ->where('statut',2)
                               ->count();
             */
                $dossiersSM=Dossier::where(function ($query)  use ($supmedic)  {
                    $query->where('reference_medic', 'like', '%N%')
                        ->where('type_dossier', 'Medical')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5)  //auto
                        ->where('affecte',$supmedic);
                })->orWhere(function ($query)  use ($supmedic)   {
                    $query->where('reference_medic', 'like', '%M%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5)  //auto
                        ->where('affecte',$supmedic);
                })->orWhere(function ($query)  use ($supmedic)   {
                    $query->where('reference_medic', 'like', '%MI%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5)  //auto
                        ->where('affecte',$supmedic);
                })->orWhere(function ($query)  use ($supmedic)   {
                    $query->where('reference_medic', 'like', '%TPA%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5)  //auto
                        ->where('affecte',$supmedic);
                })->get();

                $CdossiersSM=count($dossiersSM);
                // sup tech
                /*    $dossiersST=    Dossier::where('affecte',$suptech)
                        ->where('statut',2)
                        ->get();

                    $CdossiersST=    Dossier::where('affecte',$suptech)
                        ->where('statut',2)
                        ->count();
      */
                // Tehnique et Mixtes
                $dossiersST=Dossier::where(function ($query)  use ($suptech) {
                    $query->where('reference_medic', 'like', '%N%')
                        ->where('type_dossier', 'Technique')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5)  //auto
                        ->where('affecte',$suptech);
                })->orWhere(function ($query) use ($suptech)   {
                    $query->where('reference_medic', 'like', '%V%')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5)
                        ->where('affecte',$suptech);

                })->orWhere(function ($query)  use ($suptech)  {
                    // Mixtes
                    $query->where('reference_medic', 'like', '%N%')
                        ->where('type_dossier', 'Mixte')
                        ->where('current_status', 'actif')
                        ->where('statut', '<>', 5)
                        ->where('affecte',$suptech);

                })->get();

                $CdossiersST=count($dossiersST);

                // charge
                /*       $dossiersC=    Dossier::where('affecte',$charge)
                           ->where('statut',2)
                           ->get();

                       $CdossiersC=    Dossier::where('affecte',$charge)
                           ->where('statut',2)
                           ->count();*/


                $dossiersC=Dossier::where(function ($query) use($charge){
                    $query->where('reference_medic','like','%TN%')
                        ->where('statut', '<>', 5)
                        ->where('current_status','!=', 'Cloture')
                        ->where('affecte',$charge);
                })->orWhere(function($query) use($charge){
                    $query->where('reference_medic','like','%TM%')
                        ->where('statut', '<>', 5)
                        ->where('current_status','!=', 'Cloture')
                        ->where('affecte',$charge);
                })->orWhere(function($query)use($charge) {
                    $query->where('reference_medic','like','%TV%')
                        ->where('statut', '<>', 5)
                        ->where('current_status','!=', 'Cloture')
                        ->where('affecte',$charge);
                })->orWhere(function($query)use($charge) {
                    $query->where('reference_medic','like','%XP%')
                        ->where('statut', '<>', 5)
                        ->where('current_status','!=', 'Cloture')
                        ->where('affecte',$charge);

                })->get();

                $CdossiersC=count($dossiersC);

                // dispatcheur
                /*      $dossiersDisp=    Dossier::where('affecte',$disp)
                          ->where('statut',2)
                          ->get();

                      $CdossiersDisp=    Dossier::where('affecte',$disp)
                          ->where('statut',2)
                          ->count();
        */

                $dossiersDisp=     Dossier::where('current_status','inactif')
                    ->where('statut','<>',5)
                    ->where('affecte',$disp)
                    ->get();

                $CdossiersDisp=count($dossiersDisp);
                ?>


                <?php $c=0;
                foreach($users as $user)
                { if($c % 2 ==0){$bg=' border:2px dotted black ;';}else{$bg='';}
                $iduser=$user->id;
                $role=' ';
                if($user->id==$veilleur){$role.='(Veilleur de nuit) ';}
                if($user->id==$disp){$role.='(Dispatcheur) ';}
                if($user->id==$disptel){$role.='(Dispatcheur Téléphonique) ';}
                if($user->id==$disptel2){$role.='(Dispatcheur Téléphonique 2) ';}
                if($user->id==$disptel3){$role.='(Dispatcheur Téléphonique 3) ';}
                if($user->id==$supmedic){$role.='(Superviseur Médical) ';}
                if($user->id==$suptech){$role.='(Superviseur Technique) ';}
                if($user->id==$charge){$role.='(Chargé de transport) ';}

                $style='';

                if($user->id!=1){

                if($user->isOnline() && $user->statut!= -1 &&  $user->user_type!= 'financier' &&  $user->user_type!= 'admin' ) {
                $c++;
                $folders = Dossier::where('affecte','=',$user->id)->where('statut',5)->get();

                $countF=count($folders);
                $taille='400px;';
                if($countF < 20 || $countF == 20 ){$taille='400px';}
                if($countF > 20 && ( $countF < 40 || $countF == 40) ){$taille='600px';}
                if($countF > 40 && $countF < 80){$taille='1000px';}
                if($countF > 80 || $countF == 80){$taille='1400px';}
                if($countF >120 || $countF == 120){$taille='1800px';}
                if($countF >160){$taille='2300px';}
                echo '<h3 onclick="showUser('.$iduser.')" style="cursor:pointer;text-align:left;background-color:#a0d468;color:white;padding:10px 10px 10px 10px">'.$user->name.'  '.$user->lastname.' <small style="color:black;">'.$role.'</small> </h3>';
                echo  '<div class="userdiv" id="user-'.$iduser.'" style="display:none;margin-bottom:30px;'.$bg.';height:'.$taille.'"  >';

                foreach($folders as $folder)
                { $type=$folder['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
                $statut=$folder['statut']; $idd=$folder['id'];$ref=$folder['reference_medic'];$abn=$folder['subscriber_lastname'].' '.$folder['subscriber_name'];$idclient=$folder['customer_id'];$client= $folder['reference_customer'] /*  ClientsController::ClientChampById('name',$idclient)*/ ;?>
                <div  id="dossier-<?php echo $idd;?>" class="dossier"  style="margin-top:5px;<?php echo $style; if($statut!=2){ echo';border:2px solid black';}?>" >
                    <label style="font-size: 18px;"><?php echo $ref ;?></label>
                    <div class="infos">  <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
                        <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small>

                        <i style="float:left;color:;margin-top:10px" class="delete fa fa-trash" onclick="Delete('<?php echo $idd;?>')"></i></div>
                </div>
                <?php	}

                echo '</div>' ;}
                }
                }
                ?>


            </div>
        </div><!--panel 1-->

        <div class="panel panel-danger col-md-5" style="padding:0 ;min-height: 800px ">
            <div class="panel-heading">
                <h4 class="panel-title"></h4>

            </div>

            <ul class="nav  nav-tabs" style="margin-top:10px;margin-bottom:10px">


                <li class="nav-item">
                    <a class="nav-link" href="#panelsupmedic" data-toggle="tab" onclick="hideTabs(); showTab2() "  >
                        <i class="fa-lg fas fa-folder"></i>  Sup Médical (<?php echo  $CdossiersSM ;?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#panelsuptech" data-toggle="tab" onclick="hideTabs();showTab3() "  >
                        <i class="fa-lg fas fa-folder"></i>  Supe Tech (<?php echo $CdossiersST ;?>)
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#panelcharge" data-toggle="tab" onclick="hideTabs();showTab4(); "  >
                        <i class="fa-lg fas fa-folder"></i>  Chargé T (<?php echo $CdossiersC ;?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#paneldisp" data-toggle="tab" onclick="hideTabs();showTab5(); "  >
                        <i class="fa-lg fas fa-folder"></i>  Dispat  (<?php echo  $CdossiersDisp ;?>)
                    </a>
                </li>

            </ul>

            <ul class="nav  nav-tabs" style="margin-top:10px;margin-bottom:10px">
                <li class="nav-item  ">
                    <a class="nav-link    " href="#panelnonaff" data-toggle="tab"  onclick="showTab1();hideTab2();hideTab3();hideTab4();hideTab5();"  >
                        <i class="fa-lg fas fa-folder-open"></i>  Non affectés  (<?php echo  $Cdossiers ;?>)
                    </a>
                </li>
                <?php $i=0;
                foreach($users as $user)
                { $i++;
                    $iduser=$user->id;
                    $folders = Dossier::where('affecte','=',$user->id)->where('statut',5)->get();
                    $countF=count($folders);
                    if($user->isOnline() && $user->statut!= -1 &&  $user->user_type != 'financier' &&  $user->user_type!= 'admin'   ) {
                        echo '  <li class="nav-item  ">
                    <a class="nav-link    " href="#panelu-'.$iduser.'>" data-toggle="tab"  onclick="hideTabs();showTabs('.$iduser.');"  >
                    <i class="fa-lg fas fa-user"></i>  '.$user->name.' '.$user->lastname.' ('. $countF .')
                    </a>
                    </li> ';
                    }
                }
                ?>
            </ul>



            <div id="panelsupmedic"  class="tab-pane fade pannel" >

                <div class="row">
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInputSM" onkeyup="SMSearchf()" placeholder="N° de Dossier.." title="Taper"></div>
                    <div class="col-sm-4"><input  style="width:200px;margin-top:10px;" class="search" type="text" id="myInputSM2" onkeyup="SMSearchf2()" placeholder="Assuré.." title="Taper"></div>
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInputSM3" onkeyup="SMSearchf3()" placeholder="Réf Client.." title="Taper"></div>
                </div>
                <div class="panel-body scrollable-panel" style="display: block;min-height: 800px">
                    <div class="row" style="margin-bottom:15px;">
                        <div class="col-md-3" style="cursor:pointer;color:#F39C12" onclick="SMshowMixtes()"><i class="fa fa-lg fa-folder"></i>  <b   >Dossier Mixte</b></div>
                        <div class="col-md-3" style="cursor:pointer;color:#52BE80" onclick="SMshowMedic()" ><i class="fa fa-lg fa-folder"></i>  <b  >Dossier Medical</b></div>
                        <div class="col-md-4" style="cursor:pointer;color:#3498DB" onclick="SMshowTech()" ><i class="fa fa-lg fa-folder"></i>  <b   >Dossier Technique</b></div>
                        <div class="col-md-2" style="cursor:pointer;color:#000000;font-weight: 600" onclick="SMshowTous()" >  <b   >TOUS</b></div>
                    </div>

                    <div id="drag-elements2"  class="dragging container">

                        <?php  $type='';$style='';
                        if($CdossiersSM >0)
                        {
                        foreach($dossiersSM as $dossierI)
                        { $type=$dossierI['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
                        $idd=$dossierI['id'];
                        $immatricul=$dossierI['vehicule_immatriculation'];
                        $ref=$dossierI['reference_medic'];$abn=$dossierI['subscriber_lastname'].' '.$dossierI['subscriber_name'];$idclient=$dossierI['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>
                        <div  id="dossier-<?php echo $idd;?>" class="dossier dossier-<?php echo $type;?>"  style="margin-top:5px;<?php echo $style;?>" > <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
                            <!--<i style="float:right;color:black;margin-left:5px;margin-right:5px;" class="fa fa-folder" ></i>-->
                            <div class="infos"> <label style="font-size: 15px;"><?php echo $ref;?></label>
                                <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small><br>
                                <?php if($immatricul!='') { echo '<small style="font-size:10px">'. $immatricul .'</small>';} ?>

                            </div>
                        </div>

                        <?php	}
                        }


                        ?>
                    </div>


                </div>

            </div><!--- Panel Sup Tech --->


            <div id="panelsuptech"  class="tab-pane fade pannel" >

                <div class="row">
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInputST" onkeyup="STSearchf()" placeholder="N° de Dossier.." title="Taper"></div>
                    <div class="col-sm-4"><input  style="width:200px;margin-top:10px;" class="search" type="text" id="myInputST2" onkeyup="STSearchf2()" placeholder="Assuré.." title="Taper"></div>
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInputST3" onkeyup="STSearchf3()" placeholder="Réf Client.." title="Taper"></div>
                </div>
                <div class="panel-body scrollable-panel" style="display: block;min-height: 800px">
                    <div class="row" style="margin-bottom:15px;">
                        <div class="col-md-3" style="cursor:pointer;color:#F39C12" onclick="STshowMixtes()"><i class="fa fa-lg fa-folder"></i>  <b  >Dossier Mixte</b></div>
                        <div class="col-md-3" style="cursor:pointer;color:#52BE80" onclick="STshowMedic()" ><i class="fa fa-lg fa-folder"></i>  <b  >Dossier Medical</b></div>
                        <div class="col-md-4" style="cursor:pointer;color:#3498DB" onclick="STshowTech()" ><i class="fa fa-lg fa-folder"></i>  <b  >Dossier Technique</b></div>
                        <div class="col-md-2" style="cursor:pointer;color:#000000;font-weight: 600" onclick="STshowTous()" >  <b   >TOUS</b></div>
                    </div>

                    <div id="drag-elements3"  class="dragging container">

                        <?php  $type='';$style='';
                        if($CdossiersST >0)
                        {
                        foreach($dossiersST as $dossierI)
                        { $type=$dossierI['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
                        $idd=$dossierI['id'];
                        $immatricul=$dossierI['vehicule_immatriculation'];
                        $ref=$dossierI['reference_medic'];$abn=$dossierI['subscriber_lastname'].' '.$dossierI['subscriber_name'];$idclient=$dossierI['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>
                        <div  id="dossier-<?php echo $idd;?>" class="dossier dossier-<?php echo $type;?>"  style="margin-top:5px;<?php echo $style;?>" > <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
                            <!--<i style="float:right;color:black;margin-left:5px;margin-right:5px;" class="fa fa-folder" ></i>-->
                            <div class="infos"> <label style="font-size: 15px;"><?php echo $ref;?></label>
                                <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small><br>
                                <?php if($immatricul!='') { echo '<small style="font-size:10px">'. $immatricul .'</small>';} ?>

                            </div>
                        </div>

                        <?php	}
                        }


                        ?>
                    </div>


                </div>

            </div><!--- Panel Sup Tech --->



            <div id="panelcharge"  class="tab-pane fade pannel " >

                <div class="row">
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInputC" onkeyup="CSearchf()" placeholder="N° de Dossier.." title="Taper"></div>
                    <div class="col-sm-4"><input  style="width:200px;margin-top:10px;" class="search" type="text" id="myInputC2" onkeyup="CSearchf2()" placeholder="Assuré.." title="Taper"></div>
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInputC3" onkeyup="CSearchf3()" placeholder="Réf Client.." title="Taper"></div>
                </div>
                <div class="panel-body scrollable-panel" style="display: block;min-height: 800px">
                    <div class="row" style="margin-bottom:15px;">
                        <div class="col-md-3" style="cursor:pointer;color:#F39C12" onclick="CshowMixtes()"><i class="fa fa-lg fa-folder"></i>  <b >Dossier Mixte</b></div>
                        <div class="col-md-3" style="cursor:pointer;color:#52BE80" onclick="CshowMedic()" ><i class="fa fa-lg fa-folder"></i>  <b >Dossier Medical</b></div>
                        <div class="col-md-4" style="cursor:pointer;color:#3498DB" onclick="CshowTech()" ><i class="fa fa-lg fa-folder"></i>  <b  >Dossier Technique</b></div>
                        <div class="col-md-2" style="cursor:pointer;color:#000000;font-weight: 600" onclick="CshowTous()" >  <b   >TOUS</b></div>
                    </div>

                    <div id="drag-elements4"  class="dragging container">

                        <?php  $type='';$style='';
                        if($CdossiersC >0)
                        {
                        foreach($dossiersC as $dossierI)
                        { $type=$dossierI['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
                        $idd=$dossierI['id'];
                        $immatricul=$dossierI['vehicule_immatriculation'];
                        $ref=$dossierI['reference_medic'];$abn=$dossierI['subscriber_lastname'].' '.$dossierI['subscriber_name'];$idclient=$dossierI['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>
                        <div  id="dossier-<?php echo $idd;?>" class="dossier dossier-<?php echo $type;?>"  style="margin-top:5px;<?php echo $style;?>" > <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
                            <!--<i style="float:right;color:black;margin-left:5px;margin-right:5px;" class="fa fa-folder" ></i>-->
                            <div class="infos"> <label style="font-size: 15px;"><?php echo $ref;?></label>
                                <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small><br>
                                <?php if($immatricul!='') { echo '<small style="font-size:10px">'. $immatricul .'</small>';} ?>

                            </div>
                        </div>

                        <?php	}
                        }


                        ?>
                    </div>


                </div>

            </div><!--- Panel Chargé --->


            <div id="paneldisp"  class="tab-pane fade  pannel" >

                <div class="row">
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInputD" onkeyup="DSearchf()" placeholder="N° de Dossier.." title="Taper"></div>
                    <div class="col-sm-4"><input  style="width:200px;margin-top:10px;" class="search" type="text" id="myInputD2" onkeyup="DSearchf2()" placeholder="Assuré.." title="Taper"></div>
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInputD3" onkeyup="DSearchf3()" placeholder="Réf Client.." title="Taper"></div>
                </div>
                <div class="panel-body scrollable-panel" style="display: block;min-height: 800px">
                    <div class="row" style="margin-bottom:15px;">
                        <div class="col-md-3" style="cursor:pointer;color:#F39C12" onclick="DshowMixtes()"><i class="fa fa-lg fa-folder"></i>  <b  >Dossier Mixte</b></div>
                        <div class="col-md-3" style="cursor:pointer;color:#52BE80" onclick="DshowMedic()" ><i class="fa fa-lg fa-folder"></i>  <b  >Dossier Medical</b></div>
                        <div class="col-md-4" style="cursor:pointer;color:#3498DB" onclick="DshowTech()" ><i class="fa fa-lg fa-folder"></i>  <b   >Dossier Technique</b></div>
                        <div class="col-md-2" style="cursor:pointer;color:#000000;font-weight: 600" onclick="DshowTous()" >  <b  onclick="IshowTech()">TOUS</b></div>
                    </div>

                    <div id="drag-elements5" class="dragging container">

                        <?php  $type='';$style='';
                        if($CdossiersDisp >0)
                        {
                        foreach($dossiersDisp as $dossierI)
                        { $type=$dossierI['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
                        $idd=$dossierI['id'];
                        $immatricul=$dossierI['vehicule_immatriculation'];
                        $ref=$dossierI['reference_medic'];$abn=$dossierI['subscriber_lastname'].' '.$dossierI['subscriber_name'];$idclient=$dossierI['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>
                        <div  id="dossier-<?php echo $idd;?>" class="dossier dossier-<?php echo $type;?>"  style="margin-top:5px;<?php echo $style;?>" > <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
                            <!--<i style="float:right;color:black;margin-left:5px;margin-right:5px;" class="fa fa-folder" ></i>-->
                            <div class="infos"> <label style="font-size: 15px;"><?php echo $ref;?></label>
                                <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small><br>
                                <?php if($immatricul!='') { echo '<small style="font-size:10px">'. $immatricul .'</small>';} ?>

                            </div>
                        </div>

                        <?php	}
                        }


                        ?>
                    </div>


                </div>

            </div><!--- Panel disp --->


            <div id="panelnonaff"   class="tab-pane fade   pannel  ">


                <div class="row">
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInput" onkeyup="Searchf()" placeholder="N° de Dossier.." title="Taper"></div>
                    <div class="col-sm-4"><input  style="width:200px;margin-top:10px;" class="search" type="text" id="myInput2" onkeyup="Searchf2()" placeholder="Assuré.." title="Taper"></div>
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInput3" onkeyup="Searchf3()" placeholder="Réf Client.." title="Taper"></div>
                </div>
                <div class="panel-body scrollable-panel" style="display: block;min-height: 800px">
                    <div class="row" style="margin-bottom:15px;">
                        <div class="col-md-3" style="cursor:pointer;color:#F39C12" onclick="showMixtes()"><i class="fa fa-lg fa-folder"></i>  <b  >Dossier Mixte</b></div>
                        <div class="col-md-3" style="cursor:pointer;color:#52BE80" onclick="showMedic()" ><i class="fa fa-lg fa-folder"></i>  <b  >Dossier Medical</b></div>
                        <div class="col-md-4" style="cursor:pointer;color:#3498DB" onclick="showTech()" ><i class="fa fa-lg fa-folder"></i>  <b  >Dossier Technique</b></div>
                        <div class="col-md-2" style="cursor:pointer;color:#000000;font-weight: 600" onclick="showTous()" >  <b  onclick="showTech()">TOUS</b></div>
                    </div>

                    <div id="drag-elements" class="dragging container"  >

                        <?php  $type='';$style='';
                        if($Cdossiers >0)
                        {
                        foreach($dossiers as $dossier)
                        { $type=$dossier['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
                        $idd=$dossier['id'];
                        $immatricul=$dossier['vehicule_immatriculation']; $statut=$dossier['statut'];
                        $ref=$dossier['reference_medic'];$abn=$dossier['subscriber_lastname'].' '.$dossier['subscriber_name'];$idclient=$dossier['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>
                        <div  id="dossier-<?php echo $idd;?>" class="dossier dossier-<?php echo $type;?>"  style="margin-top:5px;<?php echo $style; if($statut!=2){ echo';border:2px solid black;';} ?>" >
                            <!--<i style="float:right;color:black;margin-left:5px;margin-right:5px;" class="fa fa-folder" ></i>--> <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
                            <div class="infos">  <label style="font-size: 15px;"><?php echo $ref;?></label>
                                <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small><br>
                                <?php if($immatricul!='') { echo '<small style="font-size:10px">'. $immatricul .'</small>';} ?>

                            </div>
                        </div>

                        <?php	}
                        }


                        ?>
                    </div>


                </div>

            </div><!--- Panel non affectés --->





            <!--**********  Tabs Users     ************---->




            <?php $c=0;
            foreach($users as $user)
            { if($c % 2 ==0){$bg=' border:2px dotted black ;';}else{$bg='';}
            $iduser=$user->id;


            if($user->id!=1){

            if($user->isOnline()  && $user->statut!= -1 &&  $user->user_type!= 'financier' &&  $user->user_type!= 'admin'  ) {
            $c++;
            $folders = Dossier::where('affecte','=',$user->id)->where('statut',5)->get();

            $countF=count($folders);
            $taille='400px;';
            if($countF < 20 || $countF == 20 ){$taille='400px';}
            if($countF > 20 && ( $countF < 40 || $countF == 40) ){$taille='600px';}
            if($countF > 40 && $countF < 80){$taille='1000px';}
            if($countF > 80 || $countF == 80){$taille='1400px';}
            if($countF >120 || $countF == 120){$taille='1800px';}
            if($countF >160){$taille='2300px';}
            ?>
            <div id="panelu-<?php echo $iduser; ?>"   class=" pannel   ">
                <div class="panel-body scrollable-panel" style="display: block;min-height: 800px">
                    <div id="drag-elements-u-<?php echo $iduser; ?>" class="dragging container"  >
                        <?php
                        $type='';$style='';

                        foreach($folders as $dossier)
                        { $type=$dossier['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
                        $idd=$dossier['id'];
                        $immatricul=$dossier['vehicule_immatriculation']; $statut=$dossier['statut'];
                        $ref=$dossier['reference_medic'];$abn=$dossier['subscriber_lastname'].' '.$dossier['subscriber_name'];$idclient=$dossier['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>
                        <div  id="dossierU-<?php echo $idd;?>" class="dossier dossierU-<?php echo $type;?>"  style="margin-top:5px;<?php echo $style; if($statut!=2){ echo';border:2px solid black;';} ?>" >
                            <!--<i style="float:right;color:black;margin-left:5px;margin-right:5px;" class="fa fa-folder" ></i>--> <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
                            <div class="infos">  <label style="font-size: 15px;"><?php echo $ref;?></label>
                                <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small><br>
                                <?php if($immatricul!='') { echo '<small style="font-size:10px">'. $immatricul .'</small>';} ?>

                            </div>
                        </div>

                        <?php	} ?>

                    </div>
                </div>
            </div>

            <?php
            }  // online
            } // not admin
            } //for users
            ?>




        </div><!--panel 2-->


        <!-- /.content -->
    </div>

    </div><!-- /main -->
    <style>

    </style>
@endsection


<script>


    function showUser(user)  {

        var   div=document.getElementById('user-'+user);
        if(div.style.display==='none')
        {
            div.style.display='block';
        }
        else
        {div.style.display='none';     }


    }

    function showTabs(user) {
        $('#panelu-'+user).css('display','block');
        //  $('#panelu-32' ).css('display','block');
    }

    function hideTabs() {

        var elements = document.getElementsByClassName('pannel');

        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'none';
        }

    }


    function hideTab1() {
        $('#panelnonaff').css('display','none');
    }
    function hideTab2() {
        $('#panelsupmedic').css('display','none');
    }
    function hideTab3() {
        $('#panelsuptech').css('display','none');
    }
    function hideTab4() {
        $('#panelcharge').css('display','none');
    }
    function hideTab5() {
        $('#paneldisp').css('display','none');
    }

    function showTab1() {
        $('#panelnonaff').css('display','block');
    }
    function showTab2() {
        $('#panelsupmedic').css('display','block');
    }
    function showTab3() {
        $('#panelsuptech').css('display','block');
    }
    function showTab4() {
        $('#panelcharge').css('display','block');
    }
    function showTab5() {
        $('#paneldisp').css('display','block');
    }


    function   showMixtes  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Mixte');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   showMedic  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Medical');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   showTech  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Technique');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   showTous  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }


    }

    function selectFolder(elm)
    {
        var idelm=elm.id;
        var ref=idelm.slice(7);

        var dossier=document.getElementById('affdoss').value=ref;
    }


    function Searchf() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("label")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }
    function Searchf2() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput2");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }

    function Searchf3() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput3");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[1];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }


    /************     Sup Medic   **************/


    function   SMshowMixtes  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements2')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Mixte');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   SMshowMedic  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements2')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Medical');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   SMshowTech  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements2')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Technique');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   SMshowTous  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }


    }


    function SMSearchf() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputSM");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements2");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("label")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }
    function SMSearchf2() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputSM2");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements2");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }

    function SMSearchf3() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputSM3");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements2");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[1];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }


    /************     Sup Tech   **************/


    function   STshowMixtes  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements3')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Mixte');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   STshowMedic  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements3')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Medical');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   STshowTech  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements3')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Technique');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   STshowTous  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }


    }


    function STSearchf() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputST");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements3");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("label")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }
    function STSearchf2() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputST2");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements3");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }

    function STSearchf3() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputST3");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements3");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[1];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }


    /************     Charge  **************/


    function   CshowMixtes  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements4')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Mixte');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   CshowMedic  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements4')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Medical');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   CshowTech  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements4')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Technique');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   CshowTous  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }


    }


    function CSearchf() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputC");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements4");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("label")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }
    function CSearchf2() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputC2");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements4");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }

    function CSearchf3() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputC3");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements4");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[1];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }



    /************     Disp   **************/


    function   DshowMixtes  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements5')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Mixte');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   DshowMedic  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements5')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Medical');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   DshowTech  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements5')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Technique');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   DshowTous  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }


    }


    function DSearchf() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputD");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements5");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("label")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }
    function DSearchf2() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputD2");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements5");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }

    function DSearchf3() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInputD3");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements5");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[1];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }
</script>





<script>


    $(document).ready(function () {
        'use strict';

        // global references

        var cont = $('.container'),
            hasMultiple = false,    // flags if there are multiple selection
            selectedItems,          // the multiple selections
            mirrorContainer,        // the floating preview
            shiftIsPressed = false;  // shift key on keyboard

        // setup draggable containers
        var drake =       dragula([
            <?php
       foreach($users as $user)
          {
               if( $user->isOnline() && $user->statut!= -1 &&  $user->user_type!= 'financier' &&  $user->user_type!= 'admin'  ) {
                   echo "document.getElementById('user-".$user->id."'),";
                   echo "document.getElementById('drag-elements-u-".$user->id."'),";
               }
          }

            ?>
  document.getElementById('drag-elements'),
  document.getElementById('drag-elements2'),
  document.getElementById('drag-elements3'),
  document.getElementById('drag-elements4'),
  document.getElementById('drag-elements5')


        ], {
            revertOnSpill: true
        });

        // handle events
        drake.on('drag', (el) => {
            // nothing happening here
        })
        .on('cloned', (clone, original, type) => {

            // are we dragging from left to right?
            var isFromLeft =
                ($(original).parent().attr('id') == 'drag-elements')
              /*  || ($(original).parent().attr('id') == 'drag-elements2')
                || ( $(original).parent().attr('id') == 'drag-elements3')
                || ( $(original).parent().attr('id') == 'drag-elements4')
                || ( $(original).parent().attr('id') == 'drag-elements5')     */   ;


        // we're dragging from left to right

            // grab the mirror container dragula creates by default
            mirrorContainer = $('.gu-mirror').first();

            // multi selected items will have this class, but we don't want it on the ones in the mirror
            mirrorContainer.removeClass('selectedItem');

            // get the multi selected items
            selectedItems = $('.selectedItem');

            // do we have multiple items selected?
            // (takes into account edge case where they start dragging from an item that hasn't been selected)
            hasMultiple = selectedItems.length > 1 || (selectedItems.length == 1 && !$(original).hasClass('selectedItem'));

            // we have multiple items selected
            if(hasMultiple) {

                // edge case: if they started dragging from an unselected item, adds the selected item class
                $('.gu-transit').addClass('selectedItem');

                // update list of selected items in case of edge case above
                selectedItems = $('.selectedItem');

                // clear the mirror container, we're going to fill it with clones of our items
                mirrorContainer.empty();

                // will track final dimensions of the mirror container
                var height = 0,
                    width = 0;

                // clone the selected items into the mirror container
                selectedItems.each(function(index) {
                    // the item
                    var item = $(this);

                    // clone the item
                    var mirror = item.clone(true);

                    // remove the state classes if necessary
                    mirror.removeClass('selectedItem gu-transit');

                    //add the clone to mirror container
                    mirrorContainer.append(mirror);
                    mirrorContainer.css('background-color', 'transparent');

                    //add drag state class to item
                    item.addClass('gu-transit');

                    // update the dimensions for the mirror container
                    var rect = item[0].getBoundingClientRect();
                    height += rect.height;
                    width = rect.width;
                });

                //set final height of mirror container
                mirrorContainer.css('height', height + 'px');
            }

    })
        .on('over', function (el, container, source) {

            // hovering over right?
            var isOverRight = $(container).attr('id') === 'drag-elements2';

            // hide the selections on the left
            //if (isOverRight) { // uncomment to show drop spots on left for multiples
            selectedItems.css('display','none');
            //}
        })
            .on('drop', function (el, target, source, sibling) {
                // convert to jquery
                target = $(target);

                // flag if dropped on right

                // are we dropping multiple items?
                if (hasMultiple) {
                    // are we adding items to the right?
                        // get the default, single dropped item
                        var droppedItem = target.find('.selectedItem').first();

                        // replace it with the content of the mirror container
                        mirrorContainer.children().insertAfter(droppedItem);

                        // remove all vestigial items from the dom
                        $('.selectedItem').remove();

                        // clear flag
                        hasMultiple = false;

                }

                // single selection case
                else {
                    // edge case: if only one item happened to be selected, remove the selected item class
                  //  right.children().removeClass('selectedItem');
                }


                console.log('target: '+target.id);
                console.log(' element: '+el.id);

                var userdiv=target.id;
                var dossdiv=el.id;
                var user=userdiv.slice( 5);
                var dossier=dossdiv.slice( 8);
                console.log('USER : '+user);
                console.log('DOSSIER : '+dossier);
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('dossiers.attribution') }}",
                    method: "POST",
                    data: {  dossierid:dossier ,agent:user, _token: _token},
                    success: function ( ) {
                        $('#dossier-'+dossier).animate({
                            opacity: '0.3',
                        });
                        $('#dossier-'+dossier).animate({
                            opacity: '1',
                        });

                    }
                });




            })
            .on('cancel', function (el, container, source) {
                // nothing happening here
            })
            .on('out', function (el, container) {
                // unhide all
                selectedItems.css('display', '');
            })
            .on('moves', function (el, container, handle) {
                // i'm going to have non-draggable line breaks in my containers
                return !$(el).is('hr');
            })
            .on('dragend', function () {
                // rebind click event handlers for the new layouts
                unbindMultiselectOnTarget();
                bindMultiselectOnSource();

                // remove state classes for multiple selections that may be back on the left
                selectedItems.removeClass("gu-transit");
                selectedItems.css('display', '');
            });

        //#######################################
        // HELPER FUNCTIONS
        //#######################################

        // sets a global flag of whether the shift key is pressed
        function bindShiftPressEvent () {
            // set flag on
            $(document).keydown(function(event){
                if(event.shiftKey)
                    shiftIsPressed = true;
            });

            // set flag off
            $(document).keyup(function(){
                shiftIsPressed = false;
            });
        }

        // enables items on left to be multiselect with a "shift + click"
        function bindMultiselectOnSource () {
            cont.children().each((index, el) => {
                $(el).off('click');
            $(el).on('click', function () {
                if (shiftIsPressed)
                    $(this).toggleClass('selectedItem');
            });
        });
        };

        // disables multiselect on items on the right
        function unbindMultiselectOnTarget () {
            cont.children().each((index, el) => {
                $(el).off('click');
        });
        }

        // initial bindings
        function init() {
            bindShiftPressEvent();
            bindMultiselectOnSource();
        }

        // start this thing
        init();
    });


</script>

<style>

    .userdiv h3{margin-top:2px!important;}
    .userdiv .delete {display:none;}

    .userdiv   {border:2px dotted grey; padding:5px 5px 5px;opactity:0.1;/*height:1300px;*/}
    .userdiv .dossier label{font-size:18px;}
    .userdiv .dossier .infos small{display:none;}
    #drag-elements .dossier .infos{display:block;}
    .dragging .dossier .infos{display:block;}

    .help{color:skyblue;}
    .tooltip-inner{
        font-size:20px;
        width:300px;
    }



    .dossier{cursor:pointer;
        width: 150px;
        height: 105px;
        margin-top: 50px;
        position: relative;
        /* background-color: #708090;*/
        /*  background-color:#52BE80  #af8e33 !important;*/color:white;font-weight:600;
        border-radius: 0 6px 6px 6px;
        box-shadow: 4px 4px 7px rgba(0, 0, 0, 0.59);
        /*  overflow:hidden;*/
        white-space:nowrap;
        text-overflow: ellipsis;
        padding:5px 5px 5px 5px!important;
    }

    .dossier:before {
        content: '';
        width: 50%;
        height: 12px;
        border-radius: 0 20px 0 0;
        background-color: #708090;
        opacity:0.5;
        position: absolute;
        top: -12px;
        left: 0px;
    }


    #drag-elements , .dragging{
        display: block;
        /* background-color: #dfdfdf;*/
        border:1px dashed #728D96;
        border-radius: 5px;
        min-height: 1200px;
        margin: 0 auto;
        padding: 20px;

    }

    #drag-elements > div ,.dragging > div {
        text-align: center;
        float: left;
        padding: 1em;
        margin: 0 1em 1em 0;
        box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
        /*border-radius: 100px;*/
        border: 2px solid #ececec;
        transition: all .5s ease;
    }

    #drag-elements > div:active ,.dragging> div:active {
        -webkit-animation: wiggle 0.3s 0s infinite ease-in;
        animation: wiggle 0.3s 0s infinite ease-in;
        opacity: .6;
        border: 2px solid #000;
    }

    #drag-elements > div:hover ,.dragging > div:hover  {
        border: 2px solid gray;
        background-color: #e5e5e5;
    }

    .userdiv    {
        /* border: 2px dashed #D9D9D9;*/
        border-radius: 5px;
        min-height: 50px;
        margin: 0 auto;
        margin-top: 10px;
        display: block;
        text-align: center;
    }

    .userdiv > div    {
        transition: all .5s;
        text-align: center;
        float: left;
        padding: 1em;
        margin: 0 1em 1em 0;
        box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
        border-radius: 5px;
        border: 2px solid skyblue;
        background: #FFFFFF;
        height: 50px;
        width: 120px;
        transition: all .5s ease;
    }
    .userdiv > div:active     {

        -webkit-animation: wiggle 0.3s 0s infinite ease-in;
        animation: wiggle 0.3s 0s infinite ease-in;
        opacity: .6;
        border: 2px solid #000;
    }

    @-webkit-keyframes wiggle {
        0% {
            -webkit-transform: rotate(0deg);
        }
        25% {
            -webkit-transform: rotate(2deg);
        }
        75% {
            -webkit-transform: rotate(-2deg);
        }
        100% {
            -webkit-transform: rotate(0deg);
        }
    }

    @keyframes wiggle {
        0% {
            transform: rotate(-2deg);
        }
        25% {
            transform: rotate(2deg);
        }
        75% {
            transform: rotate(-2deg);
        }
        100% {
            transform: rotate(0deg);
        }
    }

    .gu-mirror {
        position: fixed !important;
        margin: 0 !important;
        z-index: 9999 !important;
        padding: 1em;
    }
    .gu-hide {
        display: none !important;
    }
    .gu-unselectable {
        -webkit-user-select: none !important;
        -moz-user-select: none !important;
        -ms-user-select: none !important;
        user-select: none !important;
    }
    .gu-transit {
        opacity: 0.5;
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
        filter: alpha(opacity=50);
    }
    .gu-mirror {
        opacity: 0.5;
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
        filter: alpha(opacity=50);
    }




    @media (min-width: 1280px) {

        .userdiv .dossier label{font-size:13px!important;}
        .userdiv .dossier {width:100px;height:40px;}
    }



    /**  small **/
    @media (min-width: 768px) and (max-width: 980px) {

        .userdiv .dossier label{font-size:13px!important;}
        .userdiv .dossier {width:100px;height:40px;}
    }

    /***/
    @media (min-width: 480px) and (max-width: 767px) {

        .userdiv .dossier label{font-size:13px!important;}
        .userdiv .dossier {width:100px;height:40px;}

    }

















    /*
        body {
            background-color: #942A57;
            margin: 0 auto;
            font-family: Georgia, Helvetica;
            font-size: 17px;
            color: #ecf0f1;
            max-width: 760px;
        }

        html, body {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        *, *:before, *:after {
            -webkit-box-sizing: inherit;
            -moz-box-sizing: inherit;
            box-sizing: inherit;
        }
    */
    label {
        display: block;
        font-size: 12px;
        font-style: italic;
        margin: 5px 0;
    }

    .parent {
        background-color: rgba(255, 255, 255, 0.2);
        margin: 50px 0;
        padding: 20px;
    }

    /* dragula-specific example page styles */
    .wrapper {
        display: table;
        width: 100%;
    }
    .container {
        display: table-cell;
        background-color: rgba(255, 255, 255, 0.2);
        width: 50%;
    }
    .container:nth-child(odd) {
        background-color: rgba(0, 0, 0, 0.2);
    }

    #right.container .selectedItem {
        display: block !important;
    }
    /*
     * note that styling gu-mirror directly is a bad practice because it's too generic.
     * you're better off giving the draggable elements a unique class and styling that directly!
     */
    .container > div,
    .gu-mirror, /* single selection */
    .gu-mirror > div /* multiple selection */{
        margin: 10px;
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.2);
        transition: opacity 0.4s ease-in-out;
    }
    .container > div {
        cursor: move;
        cursor: grab;
        cursor: -moz-grab;
        cursor: -webkit-grab;
    }
    .gu-mirror {
        cursor: grabbing;
        cursor: -moz-grabbing;
        cursor: -webkit-grabbing;
        transform: rotate(-7deg);
    }
    .container > div.selectedItem {
        background-color: lightseagreen;
    }
    .container  div.selectedItem.gu-transit {
        background-color: rgba(0, 0, 0, 0.2);
    }

</style>



