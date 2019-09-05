@extends('layouts.supervislayout')

@section('content')

    <?php

    $user = auth()->user();
    $name=$user->name;
    $iduser=$user->id;
    $user_type=$user->user_type;

    $seance =  DB::table('seance')
        ->where('id','=', 1 )->first();
    $disp=$seance->dispatcheur ;
    $sup=$seance->superviseur ;
    $supmedic=$seance->superviseurmedic ;
    $suptech=$seance->superviseurtech ;
    $charge=$seance->chargetransport ;
    $disptel=$seance->dispatcheurtel ;
    $veilleur=$seance->veilleur ;

    $debut=$seance->debut ;
    $fin=$seance->fin ;

    $parametres =  DB::table('parametres')
        ->where('id','=', 1 )->first();

    $dollar=$parametres->dollar ;
    $euro=$parametres->euro ;

    $today= date('Y-m-d');

    // OM TAXIs
    $ordres_taxi = \App\OMTaxi::where('CL_heuredateRDV', 'like',$today.'%')
        ->select('id','CL_heuredateRDV','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type')
        ->orderBy('CL_heuredateRDV')
        ->get();

    //OM Ambul

    $ordres_ambul =   \App\OMAmbulance::where('CL_heuredateRDV', 'like',$today.'%')
        ->select('id','CL_heuredateRDV','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type')
        ->orderBy('CL_heuredateRDV')
         ->get();
    // OM Remorq

    $ordres_rem =  \App\OMRemorquage::where('CL_heuredateRDV', 'like',$today.'%')
        ->select('id','CL_heuredateRDV','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type')
        ->orderBy('CL_heuredateRDV')
         ->get();
    $oms = array_merge($ordres_taxi->toArray(),$ordres_ambul->toArray(),$ordres_rem->toArray() );

    // Sort the array
   /* usort($oms, function  ($element1, $element2) {
        $datetime1 = strtotime($element1['CL_heuredateRDV']);
        $datetime2 = strtotime($element2['CL_heuredateRDV']);

        return $datetime1 - $datetime2 ;
    }
    );
*/

    ?>
    <div class="panel panel-primary column col-md-12"  style="margin-left:0;margin-right:0;padding:10px 10px 10px 10px" >
              <div class="panel-heading">
                <h4 id="" class="panel-title"> Tableau de bord Missions Transport </h4>
              </div>
        				
		  <div class="panel-body" style="display: block;min-height:700px;padding:15px 15px 15px 15px">
                         <!-- Tabs -->
                        <ul class="nav  nav-tabs">
 
                            <li class="nav-item active">
                                <a class="nav-link active   " href="#"    >
                                    <i class="fas fa-2x fa-calendar-day"></i> Jour
                                </a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link  " href="{{ route('transportsemaine') }}">
                                    <i class="fas fa-2x fa-calendar-week"></i>  Semaine
                                </a>
                            </li>
                        </ul>



                       <div class="row" style="height:1200px">
                           <div class="col-md-2"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2  <?php $now=date('H');$now=intval($now); if ($now<8){echo 'style="background-color:#4fc1e9"';}?>  > 00<small>:00</small> => 07<small>:59</small> </h2></center>

                               <?php

                               $color='';$icon='';
                                                      foreach($oms as $o)
                                                      {
                                                          $ref=$o['reference_medic'];
                                                          $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                                                          $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                                                          $tel=$o['CL_contacttel'];
                                                          $de=$o['CL_lieuprest_pc'];
                                                          $vers=$o['CL_lieudecharge_dec'];
                                                          $type=$o['type'];
                                                          if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                                                          if($type=='ambulance'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                                                          if($type=='remorquage'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                                                       if($hour<8)

                                                       {  ?>
                                                      <div class="om " style="align:center;float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;;height: 250px">

                                                         <div class="row" style=" margin-bottom:5px;">
                                                              <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                                              <div class="col-md-3"><?php echo $icon; ?></div>
                                                          </div>

                                                          <div class="row" style= "margin-bottom:5px">
                                                              <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                                          </div>


                                                          <div class="row" style="margin-bottom:10px">
                                                              <div class="col-md-12 overme"  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                                              <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                                          </div>


                                                          <div class="row"  >
                                                              <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                                          </div>

                                                          <div class="row"  >
                                                              <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                                          </div>

                                                      </div>


                                                   <?php  }

                                                   }
                                                       ?>

                           </div>
                           <div class="col-md-2"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2   <?php $now=date('H');$now=intval($now); if ($now>=8 && $now<11){echo 'style="background-color:#4fc1e9"';}?> > 08 <small>:00</small> => 10<small>:59</small> </h2></center>
                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               if($hour>7 && $hour<11)

                               {  ?>
                               <div class="om " style="float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;height: 250px">

                                   <div class="row" style=" margin-bottom:5px;">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>


                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                       <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                   </div>


                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                   </div>

                               </div>


                               <?php  }

                               }
                               ?>

                           </div>
                           <div class="col-md-2"  style="border-right:2px solid #4fc1e9;min-height: 550px"   ><center><h2 <?php $now=date('H');$now=intval($now);  if ($now>=11 && $now<14){echo 'style="background-color:#4fc1e9!important"';}?>> 11<small>:00</small> => 13<small>:59</small> </h2></center>
                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               if($hour>10 && $hour<14)

                               {  ?>
                               <div class="om" style="float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;height: 250px">

                                   <div class="row" style=" margin-bottom:5px;">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>


                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme "  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                       <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                   </div>


                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                   </div>

                               </div>


                               <?php  }

                               }
                               ?>
                           </div>
                           <div class="col-md-2"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2 <?php $now=date('H');$now=intval($now);  if ($now>=14 && $now<17){echo 'style="background-color:#4fc1e9"';}?>   > 14<small>:00</small> => 16<small>:59</small></h2></center>

                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               if($hour>13 && $hour<17)

                               {  ?>
                               <div class="om" style="float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;height: 250px">

                                   <div class="row" style=" margin-bottom:5px;">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>


                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                       <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                   </div>


                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                   </div>

                               </div>


                               <?php  }

                               }
                               ?>
                           </div>
                           <div class="col-md-2"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2 <?php $now=date('H');$now=intval($now);  if ($now>=17 && $now<20){echo 'style="background-color:#4fc1e9"';}?>    > 17<small>:00</small>=> 19<small>:59</small> </h2></center>
                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               if($hour>17 && $hour<20)

                               {  ?>
                               <div class="om" style="float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;height: 250px">

                                   <div class="row" style=" margin-bottom:5px;">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>


                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme "  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                       <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                   </div>


                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                   </div>

                               </div>


                               <?php  }

                               }
                               ?>
                           </div>
                           <div class="col-md-2"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2 <?php $now=date('H');$now=intval($now);  if ($now>=20&& $now<24){echo 'style="background-color:#4fc1e9"';}?>   > 20<small>:00</small> => 23<small>:59</small> </h2></center>

                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               if($hour>19 && $hour<24)

                               {  ?>
                               <div class="om" style="float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;height: 250px">

                                   <div class="row" style=" margin-bottom:5px;">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>


                                   <div class="row" style="margin-bottom:10px">
                                       <div class="col-md-12 overme"  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                       <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                   </div>


                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                   </div>

                                   <div class="row"  >
                                       <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                   </div>

                               </div>


                               <?php  }

                               }
                               ?>
                           </div>

                       </div>
<!--
                <div class="" style="float:left;margin-top:15px;margin-right:30px;background-color:#C0392B;color:white;   ;border-radius: 20px;padding:5px 5px 5px 5px;width:300px">

                       <div class="row" style=" margin-bottom:5px;">
                           <div class="col-md-9"><i class="fa fa-folder"></i> 15N00026</div>
                           <div class="col-md-3"><i class="fas fa-2x fa-ambulance"></i></div>
                       </div>

                    <div class="row" style= "margin-bottom:5px">
                        <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> 09:00</div>
                    </div>

                    <div class="row" style="margin-bottom:10px">
                        <div class="col-md-12 "  ><i class="fas fa-portrait"></i>  Affes Mohamed Ali</div>
                           <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>  +216 50 235 666</div>
                       </div>


                    <div class="row"  >
                        <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small> Sahloul ,sousse </div>
                    </div>

                    <div class="row"  >
                        <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> Hopital Farhat Hached</div>
                    </div>


                  </div>

                       <div class="" style="float:left;margin-top:15px;margin-right:30px;background-color:#D4AC0D; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;width:300px">

                          <div class="row" style=" margin-bottom:5px;">
                               <div class="col-md-9"><i class="fa fa-folder"></i> 19N00035</div>
                               <div class="col-md-3"><i class="fas fa-2x fa-taxi"></i></div>
                           </div>

                           <div class="row" style= "margin-bottom:5px">
                               <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> 09:00</div>
                           </div>


                           <div class="row" style="margin-bottom:10px">
                               <div class="col-md-12 "  ><i class="fas fa-portrait"></i>  Affes Mohamed Ali</div>
                               <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>  +216 50 235 666</div>
                           </div>


                           <div class="row"  >
                               <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small> Sahloul ,sousse </div>
                           </div>

                           <div class="row"  >
                               <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> Hopital Farhat Hached</div>
                           </div>

                       </div>



                       <div class="" style="float:left;margin-top:15px;margin-right:30px;background-color:#2874A6; color:white;  border-radius: 20px;padding:5px 5px 5px 5px;width:300px">

                           <div class="row" style=" margin-bottom:5px;">
                               <div class="col-md-9"><i class="fa fa-folder"></i> 18N00063</div>
                               <div class="col-md-3"><i class="fas fa-2x fa-truck-pickup"></i></div>
                           </div>

                           <div class="row" style= "margin-bottom:5px">
                               <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> 10:00</div>
                           </div>

                           <div class="row" style="margin-bottom:10px">
                               <div class="col-md-12"><i class="fas fa-portrait"></i>  Nom prenom abonné</div>
                               <div class="col-md-12"><i class="fas fa-mobile-alt"></i>  +216 50 235 666</div>

                           </div>


                           <div class="row"  >
                               <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small> Sahloul ,sousse </div>
                           </div>

                           <div class="row"  >
                               <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> Hopital Farhat Hached</div>
                           </div>

                       </div>

-->

                       <?php
/*

                       foreach($ordres_taxi as $ot)
                       {
                           $ref=$ot->reference_medic;
                           $benef=$ot->subscriber_name.' '.$ot->subscriber_lastname;
                           $heure=$ot->CL_heure_RDV;$heure= substr($heure,0,5);
                           $tel=$ot->CL_contacttel;
                           $de=$ot->CL_lieuprest_pc;
                           $vers=$ot->CL_lieudecharge_dec;

                          ?>
                       <div class="" style="float:left;margin-top:15px;margin-right:30px;background-color:#D4AC0D; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;width:300px;height: 250px">

                          <div class="row" style=" margin-bottom:5px;">
                               <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                               <div class="col-md-3"><i class="fas fa-2x fa-taxi"></i></div>
                           </div>

                           <div class="row" style= "margin-bottom:5px">
                               <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                           </div>


                           <div class="row" style="margin-bottom:10px">
                               <div class="col-md-12 "  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                               <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                           </div>


                           <div class="row"  >
                               <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                           </div>

                           <div class="row"  >
                               <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                           </div>

                       </div>


                    <?php  }
                      */ ?>




                       <?php

/*
                       foreach($ordres_ambul as $oa)
                       {
                           $ref=$oa->reference_medic;
                           $benef=$oa->subscriber_name.' '.$oa->subscriber_lastname;
                           $heure=$oa->CL_heure_RDV;$heure= substr($heure,0,5);
                           $tel=$oa->CL_contacttel;
                           $de=$oa->CL_lieuprest_pc;
                           $vers=$oa->CL_lieudecharge_dec;

                           ?>

                           <div class="" style="float:left;margin-top:15px;margin-right:30px;background-color:#C0392B; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;width:300px;height: 250px">

                               <div class="row" style=" margin-bottom:5px;">
                                   <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                   <div class="col-md-3"><i class="fas fa-2x fa-ambulance"></i></div>
                               </div>

                               <div class="row" style= "margin-bottom:5px">
                                   <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                               </div>


                               <div class="row" style="margin-bottom:10px">
                                   <div class="col-md-12 "  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                   <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                               </div>


                               <div class="row"  >
                                   <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                               </div>

                               <div class="row"  >
                                   <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                               </div>

                           </div>

                       <?php  }
*/

?>


                       <?php


                   /*    foreach($ordres_rem as $or)
                       {
                       $ref=$or->reference_medic;
                       $benef=$or->subscriber_name.' '.$or->subscriber_lastname;
                       $heure=$or->CL_heure_RDV;$heure= substr($heure,0,5);
                       $tel=$or->CL_contacttel;
                       $de=$or->CL_lieuprest_pc;
                       $vers=$or->CL_lieudecharge_dec;

                       ?>

                       <div class="" style="float:left;margin-top:15px;margin-right:30px;background-color:#2874A6; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;width:300px;height: 250px">

                           <div class="row" style=" margin-bottom:5px;">
                               <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                               <div class="col-md-3"><i class="fas fa-2x fa-truck-pickup"></i></div>
                           </div>

                           <div class="row" style= "margin-bottom:5px">
                               <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                           </div>


                           <div class="row" style="margin-bottom:10px">
                               <div class="col-md-12 "  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                               <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                           </div>


                           <div class="row"  >
                               <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                           </div>

                           <div class="row"  >
                               <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                           </div>

                       </div>

                       <?php  }

                   */
                       ?>






          </div><!-- PANEL BODY -->
		</div><!-- PANEL  -->



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


</script>