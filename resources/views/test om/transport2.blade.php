@extends('layouts.supervislayout')

@section('content')

    <?php
    setlocale(LC_TIME, "fr_FR");
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
    $veilleur=$seance->veilleur ;

    $debut=$seance->debut ;
    $fin=$seance->fin ;

    $parametres =  DB::table('parametres')
        ->where('id','=', 1 )->first();


    $today= date('Y-m-d');

    // OM TAXIs
    $ordres_taxi = \App\OMTaxi::where('dateheuredep', 'like',$today.'%')
        ->where('dernier',1)
         ->select('id','dateheuredep','affectea','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type','lchauff','lvehicule')
        ->orderBy('dateheuredep')
        ->get();

    //OM Ambul

    $ordres_ambul =   \App\OMAmbulance::where('dateheuredep', 'like',$today.'%')
        ->where('dernier',1)
          ->select('id','dateheuredep','affectea','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type','lvehicule','lambulancier1')
        ->orderBy('dateheuredep')
         ->get();
    // OM Remorq

    $ordres_rem =  \App\OMRemorquage::where('dateheuredep', 'like',$today.'%')
        ->where('dernier',1)
         ->select('id','dateheuredep','affectea','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type','lchauff','lvehicule')
         ->orderBy('dateheuredep')
         ->get();
    $oms = array_merge($ordres_taxi->toArray(),$ordres_ambul->toArray(),$ordres_rem->toArray() );

    function cmp($a, $b)
    {
        return strcmp($a["dateheuredep"], $b["dateheuredep"]);
    }

    usort($oms, "cmp");




    ?>
    <div class="panel panel-primary column col-md-12"  style="margin-left:0;margin-right:0;padding:10px 10px 10px 10px" >
              <div class="panel-heading">
                <h4 id="" class="panel-title"> Tableau de bord Missions Transport </h4>
              </div>
        				
		  <div class="panel-body" style="display: block;min-height:700px;padding:15px 15px 15px 15px">
                         <!-- Tabs -->
                        <ul class="nav  nav-tabs">
 
                            <li class="nav-item ">
                                <a class="nav-link    " href="{{ route('transport') }}"  >
                                    <i class="fas fa-2x fa-calendar-day"></i> Jour v
                                </a>
                            </li>

                            <li class="nav-item active">
                                <a class="nav-link active   " href="#"    >
                                    <i class="fas fa-2x fa-calendar-day"></i> Jour h
                                </a>
                            </li>


                            <li class="nav-item">
                                <a class="nav-link  " href="{{ route('transportsemaine') }}">
                                    <i class="fas fa-2x fa-calendar-week"></i>  Semaine
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link  "  href="{{ route('transporttous') }}"  >
                                    <i class="fas fa-2x fa-list"></i>  Tous
                                </a>
                            </li>

                        </ul>



                       <table  style="width:100%" >
                           <tr   style="border-right:2px solid #4fc1e9;min-height: 550px"><td><center><h2  <?php $now=date('H');$now=intval($now); if ($now<8){echo 'style="background-color:#4fc1e9"';}?>  > 00<small>:00</small> => 07<small>:59</small> </h2></center>

                               <?php

                               $color='';$icon='';
                                                      foreach($oms as $o)
                                                      {
                                                          $emp=$o['emplacement'];  $emppos=strpos($emp, '/OrdreMissions/'); $empsub=substr($emp, $emppos);
                                                            $ref=$o['reference_medic'];
                                                          $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                                                          $heureT=$o['dateheuredep'];$heure= substr($heureT,11,5);  $hour=intval(substr($heureT,11,3));
                                                          $tel=$o['CL_contacttel'];
                                                          $de=$o['CL_lieuprest_pc'];
                                                          $vers=$o['CL_lieudecharge_dec'];
                                                          $type=$o['type'];
                                                          $veh=$o['lvehicule'];
                                                          $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                                                          if($type=='taxi'){  $chauff=$o['lchauff']; $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                                                          if($type=='ambulance'){  $chauff=$o['lambulancier1'];  $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                                                          if($type=='remorquage'){  $chauff=$o['lchauff']; $color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                                                       if($hour<8)

                                                       {  ?>
                                                      <div class="om " style="background-color:<?php echo $color2;?>;float:left;margin-top:15px;margin-right:30px; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;;height: 400px">

                                                         <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>; ">
                                                              <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                                              <div class="col-md-3"><?php echo $icon; ?></div>
                                                          </div>

                                                          <div class="row" style= "margin-bottom:5px">
                                                              <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                                          </div>
                                                          <div class="row" style= "margin-bottom:5px">
                                                              <div   style="background-color:black;color:white;text-align: center;font-size: 20px"><a href="#" onclick="modalattach('<?php echo 'OM '.ucwords($type).' '.$ref.' '.$benef;?>','<?php echo URL::asset('storage'.$empsub);?>')"><i class="fas fa-file-alt"></i> Ouvrir </a></div>
                                                          </div>

                                                          <div class="row" style="margin-bottom:10px">
                                                              <div class="col-md-12 overme"  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                                              <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                                          </div>
                                                          <div class="row" style="margin-bottom:10px">
                                                              <div class="col-md-12 overme"  ><i class="fas fa-user-alt"></i>  <?php echo $chauff; ?></div>
                                                              <div class="col-md-12 overme "  ><i class="fas fa-car"></i>   <?php echo $veh; ?></div>
                                                          </div>


                                                          <div class="row"  >
                                                              <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                                          </div>

                                                          <div class="row"  >
                                                              <div class="col-md-12 overme"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                                          </div>

                                                      </div>


                                                   <?php  }

                                                   }
                                                       ?>

                               </td> </tr>
                           <tr    style="border-right:2px solid #4fc1e9;min-height: 550px"><td><center><h2   <?php $now=date('H');$now=intval($now); if ($now>=8 && $now<11){echo 'style="background-color:#4fc1e9"';}?> > 08 <small>:00</small> => 10<small>:59</small> </h2></center>
                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $emp=$o['emplacement'];  $emppos=strpos($emp, '/OrdreMissions/'); $empsub=substr($emp, $emppos);
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heureT=$o['dateheuredep'];$heure= substr($heureT,11,5);  $hour=intval(substr($heureT,11,3));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                                   $veh=$o['lvehicule'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               if($type=='taxi'){  $chauff=$o['lchauff'];$color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){  $chauff=$o['lambulancier1']; $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){  $chauff=$o['lchauff'];$color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               if($hour>7 && $hour<11)

                               {  ?>
                               <div class="om " style="background-color:<?php echo $color2;?>;float:left;margin-top:15px;margin-right:30px; ; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;height: 400px">

                                   <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>;">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>
                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:black;color:white;text-align: center;font-size: 20px"><a href="#" onclick="modalattach('<?php echo 'OM '.ucwords($type).' '.$ref.' '.$benef;?>','<?php echo URL::asset('storage'.$empsub);?>')"><i class="fas fa-file-alt"></i> Ouvrir </a></div>
                                   </div>

                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                       <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                   </div>
                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-user-alt"></i>  <?php echo $chauff; ?></div>
                                       <div class="col-md-12 overme "  ><i class="fas fa-car"></i>   <?php echo $veh; ?></div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12 overme"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                   </div>

                               </div>


                               <?php  }

                               }
                               ?>
                               </td>
                           </tr>
                           <tr   style="border-right:2px solid #4fc1e9;min-height: 550px"   ><td><center><h2 <?php $now=date('H');$now=intval($now);  if ($now>=11 && $now<14){echo 'style="background-color:#4fc1e9!important"';}?>> 11<small>:00</small> => 13<small>:59</small> </h2></center>
                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $emp=$o['emplacement'];  $emppos=strpos($emp, '/OrdreMissions/'); $empsub=substr($emp, $emppos);
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heureT=$o['dateheuredep'];$heure= substr($heureT,11,5);  $hour=intval(substr($heureT,11,3));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               $type=$o['type'];
                                   $veh=$o['lvehicule'];
                               if($type=='taxi'){  $chauff=$o['lchauff'];$color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){  $chauff=$o['lambulancier1'];  $chauff=$o['lambulancier1'];  $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){  $chauff=$o['lchauff'];$color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               if($hour>10 && $hour<14)

                               {  ?>
                               <div class="om" style="background-color:<?php echo $color2;?>;float:left;margin-top:15px;margin-right:30px;; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;height: 400px">

                                   <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>
                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:black;color:white;text-align: center;font-size: 20px"><a href="#" onclick="modalattach('<?php echo 'OM '.ucwords($type).' '.$ref.' '.$benef;?>','<?php echo URL::asset('storage'.$empsub);?>')"><i class="fas fa-file-alt"></i> Ouvrir </a></div>
                                   </div>

                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme "  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                       <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                   </div>
                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-user-alt"></i>  <?php echo $chauff; ?></div>
                                       <div class="col-md-12 overme "  ><i class="fas fa-car"></i>   <?php echo $veh; ?></div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12 overme"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                   </div>

                               </div>


                               <?php  }

                               }
                               ?>
                               </td>
                           </tr>
                           <tr    style="border-right:2px solid #4fc1e9;min-height: 550px"><td><center><h2 <?php $now=date('H');$now=intval($now);  if ($now>=14 && $now<17){echo 'style="background-color:#4fc1e9"';}?>   > 14<small>:00</small> => 16<small>:59</small></h2></center>

                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $emp=$o['emplacement'];  $emppos=strpos($emp, '/OrdreMissions/'); $empsub=substr($emp, $emppos);
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heureT=$o['dateheuredep'];$heure= substr($heureT,11,5);  $hour=intval(substr($heureT,11,3));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                                   $veh=$o['lvehicule'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               if($type=='taxi'){  $chauff=$o['lchauff'];$color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){  $chauff=$o['lambulancier1']; $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){  $chauff=$o['lchauff'];$color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               if($hour>13 && $hour<17)

                               {  ?>
                               <div class="om" style="background-color:<?php echo $color2;?>;float:left;margin-top:15px;margin-right:30px; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;height: 400px">

                                   <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>
                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:black;color:white;text-align: center;font-size: 20px"><a href="#" onclick="modalattach('<?php echo 'OM '.ucwords($type).' '.$ref.' '.$benef;?>','<?php echo URL::asset('storage'.$empsub);?>')"><i class="fas fa-file-alt"></i> Ouvrir </a></div>
                                   </div>

                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                       <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                   </div>
                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-user-alt"></i>  <?php echo $chauff; ?></div>
                                       <div class="col-md-12 overme "  ><i class="fas fa-car"></i>   <?php echo $veh; ?></div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12 overme"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                   </div>

                               </div>


                               <?php  }

                               }
                               ?>
                               </td>
                           </tr>
                           <tr    style="border-right:2px solid #4fc1e9;min-height: 550px"><td><center><h2 <?php $now=date('H');$now=intval($now);  if ($now>=17 && $now<20){echo 'style="background-color:#4fc1e9"';}?>    > 17<small>:00</small>=> 19<small>:59</small> </h2></center>
                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $emp=$o['emplacement'];  $emppos=strpos($emp, '/OrdreMissions/'); $empsub=substr($emp, $emppos);
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heureT=$o['dateheuredep'];$heure= substr($heureT,11,5);  $hour=intval(substr($heureT,11,3));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                                   $veh=$o['lvehicule'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               if($type=='taxi'){  $chauff=$o['lchauff'];$color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){  $chauff=$o['lambulancier1']; $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){  $chauff=$o['lchauff'];$color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               if($hour>16 && $hour<20)

                               {  ?>
                               <div class="om" style="background-color:<?php echo $color2;?>;float:left;margin-top:15px;margin-right:30px; ; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;height:400px">

                                   <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>;">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>
                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:black;color:white;text-align: center;font-size: 20px"><a href="#" onclick="modalattach('<?php echo 'OM '.ucwords($type).' '.$ref.' '.$benef;?>','<?php echo URL::asset('storage'.$empsub);?>')"><i class="fas fa-file-alt"></i> Ouvrir </a></div>
                                   </div>

                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme "  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                       <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                   </div>

                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-user-alt"></i>  <?php echo $chauff; ?></div>
                                       <div class="col-md-12 overme "  ><i class="fas fa-car"></i>   <?php echo $veh; ?></div>
                                   </div>
                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12 overme"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                   </div>

                               </div>


                               <?php  }

                               }
                               ?>
                               </td>
                           </tr>
                           <tr    style="border-right:2px solid #4fc1e9;min-height: 550px"><td><center><h2 <?php $now=date('H');$now=intval($now);  if ($now>=20&& $now<24){echo 'style="background-color:#4fc1e9"';}?>   > 20<small>:00</small> => 23<small>:59</small> </h2></center>

                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $emp=$o['emplacement'];  $emppos=strpos($emp, '/OrdreMissions/'); $empsub=substr($emp, $emppos);
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heureT=$o['dateheuredep'];$heure= substr($heureT,11,5);  $hour=intval(substr($heureT,11,3));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                                   $veh=$o['lvehicule'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               if($type=='taxi'){ $chauff=$o['lchauff']; $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){  $chauff=$o['lambulancier1']; $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){  $chauff=$o['lchauff'];$color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               if($hour>19 && $hour<24)

                               {  ?>
                               <div class="om" style="background-color:<?php echo $color2;?>;;float:left;margin-top:15px;margin-right:30px;color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;height: 400px">

                                   <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>;">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>
                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:black;color:white;text-align: center;font-size: 20px"><a href="#" onclick="modalattach('<?php echo 'OM '.ucwords($type).' '.$ref.' '.$benef;?>','<?php echo URL::asset('storage'.$empsub);?>')"><i class="fas fa-file-alt"></i> Ouvrir </a></div>
                                   </div>

                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                       <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                   </div>

                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-user-alt"></i>  <?php echo $chauff; ?></div>
                                       <div class="col-md-12 overme "  ><i class="fas fa-car"></i>   <?php echo $veh; ?></div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12 overme"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                   </div>

                               </div>


                               <?php  }

                               }
                               ?>
                               </td>
                           </tr>

                       </table>




          </div><!-- PANEL BODY -->
		</div><!-- PANEL  -->



    <!-- Modal Ouvrir Fichier-->
    <div class="modal fade" id="openattach"  role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width:900px;height: 450px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="attTitle" style="text-align:center">OM</h2>
                </div>
                <div class="modal-body">
                    <div class="card-body">


                        <iframe id="attachiframe" src="" frameborder="0" style="width:100%;min-height:640px;"></iframe>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <style>h2{background-color: grey;color:white;height: 40px;padding-top:5px;}
        h2 small{color:#FCFBFB;}





        /***  BIG ***/
        @media  (min-width: 1400px) {
            .om{width:300px;}

        }
        @media   (min-width: 1600px) {
            .om{width:300px;}

        }
        @media   (min-width: 1900px) {
            .om{width:300px;}

        }


        @media  (min-width : 1300px)    {
            .om{width:300px;}

        }


        @media (min-width: 1024px) {
    .om{width:225px;}

        }





            /****  S M A L L  ---  D E V I C E S ****/
            @media  (max-width: 1280px)  /*** 150 % ***/  {
                .om{width:180px;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                }
                .om i {display:none;}

            }

            /**************/
            @media (max-width: 1024px) /***     ***/  {
                .om{width:160px;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;}
                .om i {display:none;}

            }


            @media (max-width: 1100px) /*** 175 % ***/  {

                .om{width:150px;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;}
                .om i {display:none;}

            }/********/


            @media (min-width: 768px) and (max-width: 980px) {
                .om{width:150px;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;}
                .om i {display:none;}

            }/**/

            @media (min-width: 480px) and (max-width: 767px) {
                .om{width:150px;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;}
                .om i {display:none;}

            }/************/




    </style>

    @endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>


    function modalattach(titre,emplacement)
    {
        $("#attTitle").text(titre);


            document.getElementById('attachiframe').src =emplacement;
            document.getElementById('attachiframe').style.display='block';

        $("#openattach").modal('show');
    }




</script>