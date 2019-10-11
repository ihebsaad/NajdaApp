@extends('layouts.supervislayout')

@section('content')

    <?php

    use Illuminate\Support\Carbon;$user = auth()->user();
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

    $dollar=$parametres->dollar ;
    $euro=$parametres->euro ;

    $today= date('Y-m-d');

    // OM TAXIs
    $ordres_taxi = \App\OMTaxi::where('CL_heuredateRDV', '>=', Carbon::now()->toDateString())
        ->select('id','CL_heuredateRDV','affectea','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type')
        ->orderBy('CL_heuredateRDV')
        ->get();

    //OM Ambul

    $ordres_ambul =   \App\OMAmbulance::where('CL_heuredateRDV',  '>=', Carbon::now()->toDateString())
        ->select('id','CL_heuredateRDV','affectea','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type')
        ->orderBy('CL_heuredateRDV')
         ->get();
    // OM Remorq

    $ordres_rem =  \App\OMRemorquage::where('CL_heuredateRDV',  '>=', Carbon::now()->toDateString())
        ->select('id','CL_heuredateRDV','affectea','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type')
        ->orderBy('CL_heuredateRDV')
         ->get();
    $oms = array_merge($ordres_taxi->toArray(),$ordres_ambul->toArray(),$ordres_rem->toArray() );

    function cmp($a, $b)
    {
        return strcmp($a["CL_heuredateRDV"], $b["CL_heuredateRDV"]);
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

                  <li class="nav-item  ">
                      <a class="nav-link    "    href="{{ route('transport') }}"  >
                          <i class="fas fa-2x fa-calendar-day"></i> Jour
                      </a>
                  </li>

                  <li class="nav-item active">
                      <a class="nav-link active " href="#">
                          <i class="fas fa-2x fa-calendar-week"></i>  Semaine
                      </a>
                  </li>
              </ul>

                       <div class="row seven-cols" style="height:1200px">
                           <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2 style="background-color: #4fc1e9!important;font-weight: 800"> <?php echo date('l d/m/y');?> </h2></center>

                               <?php

                               $color='';$icon='';
                                                      foreach($oms as $o)
                                                      {
                                                          $date=$o['CL_heuredateRDV'];
                                                          $ref=$o['reference_medic'];
                                                          $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                                                          $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                                                          $tel=$o['CL_contacttel'];
                                                          $de=$o['CL_lieuprest_pc'];
                                                          $vers=$o['CL_lieudecharge_dec'];
                                                          $type=$o['type'];
                                                          $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                                                         if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                                                          if($type=='ambulance'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                                                          if($type=='remorquage'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                                                         $Date= date("Y-m-d", strtotime($date));
                                                          $day=$today;
                                                       if($Date==$day)

                                                       {  ?>
                                                <div  class="om " style=" float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color2; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;min-height: 120px"  id="div-<?php echo $type;?>-<?php echo $o['id'];?>"  ondblclick="display(this);">

                                                    <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>; ">
                                                              <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                                              <div class="col-md-3"><?php echo $icon; ?></div>
                                                          </div>

                                                          <div class="row" style= "margin-bottom:5px">
                                                              <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                                          </div>

                                                          <div id="om-<?php echo $type;?>-<?php echo $o['id'];?>"  style="display:none">
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

                                                  </div>


                                                   <?php  }

                                                   }
                                                       ?>

                           </div>
                           <?php
                           $day= new DateTime($today);
                           $day->modify('+1 day');
                           $day=$day->format('Y-m-d');
                           ?>
                           <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo date("l d/m/y", strtotime($day)); ?> </h2></center>
                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $date=$o['CL_heuredateRDV'];
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               $Date= date("Y-m-d", strtotime($date));

                               if($Date==$day)
                               {  ?>
                               <div class="om " style=" float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color2; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;min-height: 120px"  id="div-<?php echo $type;?>-<?php echo $o['id'];?>"  ondblclick="display(this);">

                                   <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>; ">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>

                                   <div id="om-<?php echo $type;?>-<?php echo $o['id'];?>"  style="display:none">
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

                               </div>


                               <?php  }

                               }
                               ?>

                           </div>
                           <?php
                           $day= new DateTime($today);
                           $day->modify('+2 day');
                           $day=$day->format('Y-m-d');
                           ?>
                           <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo date("l d/m/y", strtotime($day)); ?> </h2></center>
                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $date=$o['CL_heuredateRDV'];
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               $Date= date("Y-m-d", strtotime($date));

                               if($Date==$day)
                               {  ?>
                               <div class="om " style=" float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color2; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;min-height: 120px"  id="div-<?php echo $type;?>-<?php echo $o['id'];?>"  ondblclick="display(this);">

                                   <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>; ">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>

                                   <div id="om-<?php echo $type;?>-<?php echo $o['id'];?>"  style="display:none">
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

                               </div>


                               <?php  }

                               }
                               ?>
                           </div>
                           <?php
                           $day= new DateTime($today);
                           $day->modify('+3 day');
                           $day=$day->format('Y-m-d');
                           ?>
                           <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo date("l d/m/y", strtotime($day)); ?> </h2></center>

                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $date=$o['CL_heuredateRDV'];
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               $Date= date("Y-m-d", strtotime($date));

                               if($Date==$day)
                               {  ?>
                               <div class="om " style=" float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color2; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;min-height: 120px"  id="div-<?php echo $type;?>-<?php echo $o['id'];?>"  ondblclick="display(this);">

                                   <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>; ">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>

                                   <div id="om-<?php echo $type;?>-<?php echo $o['id'];?>"  style="display:none">
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

                               </div>


                               <?php  }

                               }
                               ?>
                           </div>
                           <?php
                           $day= new DateTime($today);
                           $day->modify('+4 day');
                           $day=$day->format('Y-m-d');
                           ?>
                           <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo date("l d/m/y", strtotime($day)); ?> </h2></center>
                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $date=$o['CL_heuredateRDV'];
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               $Date= date("Y-m-d", strtotime($date));

                               if($Date==$day)
                               {  ?>
                               <div class="om " style=" float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color2; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;min-height: 120px"  id="div-<?php echo $type;?>-<?php echo $o['id'];?>"  ondblclick="display(this);">

                                   <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>; ">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>

                                   <div id="om-<?php echo $type;?>-<?php echo $o['id'];?>"  style="display:none">
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

                               </div>


                               <?php  }

                               }
                               ?>
                           </div>
                           <?php
                           $day= new DateTime($today);
                           $day->modify('+5 day');
                           $day=$day->format('Y-m-d');
                           ?>
                           <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo date("l d/m/y", strtotime($day)); ?> </h2></center>

                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $date=$o['CL_heuredateRDV'];
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               $Date= date("Y-m-d", strtotime($date));

                               if($Date==$day)
                               {  ?>
                               <div class="om " style=" float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color2; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;min-height: 120px"  id="div-<?php echo $type;?>-<?php echo $o['id'];?>"  ondblclick="display(this);">

                                   <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>; ">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>


                                   <div id="om-<?php echo $type;?>-<?php echo $o['id'];?>"  style="display:none">
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

                               </div>


                               <?php  }

                               }
                               ?>
                           </div>
                           <?php
                           $day= new DateTime($today);
                           $day->modify('+6 day');
                           $day=$day->format('Y-m-d');
                           ?>
                           <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo date("l d/m/y", strtotime($day)); ?> </h2></center>

                               <?php

                               $color='';$icon='';
                               foreach($oms as $o)
                               {
                               $date=$o['CL_heuredateRDV'];
                               $ref=$o['reference_medic'];
                               $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                               $heure=$o['CL_heure_RDV'];$heure= substr($heure,0,5); $hour=intval(substr($heure,0,2));
                               $tel=$o['CL_contacttel'];
                               $de=$o['CL_lieuprest_pc'];
                               $vers=$o['CL_lieudecharge_dec'];
                               $type=$o['type'];
                               $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                               if($type=='taxi'){ $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                               if($type=='ambulance'){ $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                               if($type=='remorquage'){ $color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}
                               $Date= date("Y-m-d", strtotime($date));

                               if($Date==$day)
                               {  ?>
                               <div class="om " style=" float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color2; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;min-height: 120px"  id="div-<?php echo $type;?>-<?php echo $o['id'];?>"   ondblclick="display(this);">

                                   <div class="row" style=" margin-bottom:5px;">
                                       <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                       <div class="col-md-3"><?php echo $icon; ?></div>
                                   </div>

                                   <div class="row" style= "margin-bottom:5px">
                                       <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                   </div>

                                   <div id="om-<?php echo $type;?>-<?php echo $o['id'];?>"  style="display:none">
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

                               </div>


                               <?php  }

                               }
                               ?>
                           </div>


                       </div>








          </div><!-- PANEL BODY -->
		</div><!-- PANEL  -->



    <style>h2{background-color: grey;color:white;height: 40px;padding-top:5px;}
        h2 small{color:#FCFBFB;}





        /***  BIG ***/

        @media tv    {
            .om{width:270px;}


        }

        @media (min-width: 1024px) {
            .om{width:200px;}

        }



        /****  S M A L L  ---  D E V I C E S ****/
        @media  (max-width: 1280px)  /*** 150 % ***/  {
            .om{width:150px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }
            .om i {display:none;}

        }

        /**************/
        @media (max-width: 1024px) /***     ***/  {
            .om{width:135px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;}
            .om i {display:none;}

        }


        @media (max-width: 1100px) /*** 175 % ***/  {

            .om{width:135px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;}
            .om i {display:none;}

        }/********/


        @media (min-width: 768px) and (max-width: 980px) {
            .om{width:135px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;}
            .om i {display:none;}

        }/**/

        @media (min-width: 480px) and (max-width: 767px) {
            .om{width:135px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;}
            .om i {display:none;}

        }/************/







/************** SEVEN Columns *****************************/
        @media (min-width: 768px){
            .seven-cols .col-md-1,
            .seven-cols .col-sm-1,
            .seven-cols .col-lg-1  {
                width: 100%;
                *width: 100%;
            }
        }

        @media (min-width: 992px) {
            .seven-cols .col-md-1,
            .seven-cols .col-sm-1,
            .seven-cols .col-lg-1 {
                width: 14.285714285714285714285714285714%;
                *width: 14.285714285714285714285714285714%;
            }
        }

        /**
         *  The following is not really needed in this case
         *  Only to demonstrate the usage of @media for large screens
         */
        @media (min-width: 1200px) {
            .seven-cols .col-md-1,
            .seven-cols .col-sm-1,
            .seven-cols .col-lg-1 {
                width: 14.285714285714285714285714285714%;
                *width: 14.285714285714285714285714285714%;
            }
        }
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

    function display(elm) {
        var id=elm.id;
        var div = id.slice(4);

        var   div=document.getElementById('om-'+div);
        if(div.style.display==='none')
        {
            div.style.display='block';
            //div.style.height='250px';
        }
        else
        {
            div.style.display='none';
            //div.style.height='100px';

        }

    }


</script>