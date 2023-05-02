@extends('layouts.supervislayout')

@section('content')

    <?php
     setlocale(LC_ALL, 'fr_FR');

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


    $month = date('m');
    $year = date('Y');
    $date = date('d');
    $fmt = new IntlDateFormatter('fr_FR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'Europe/Paris',
        IntlDateFormatter::GREGORIAN);
    //	$date=date('l A d/m/Y');
    $d1 = mktime(0, 0, 0, $month , $date, $year);
    $d2 = mktime(0, 0, 0, $month , $date+1, $year);
    $d3 = mktime(0, 0, 0, $month , $date+2, $year);
    $d4 = mktime(0, 0, 0, $month , $date+3, $year);
    $d5 = mktime(0, 0, 0, $month , $date+4, $year);
    $d6 = mktime(0, 0, 0, $month , $date+5, $year);
    $d7 = mktime(0, 0, 0, $month , $date+6, $year);
    $sd1 =  $fmt->format($d1);
    $sd2 =  $fmt->format($d2);
    $sd3 =  $fmt->format($d3);
    $sd4 =  $fmt->format($d4);
    $sd5 =  $fmt->format($d5);
    $sd6 =  $fmt->format($d6);
    $sd7 =  $fmt->format($d7);

    $today= date('Y-m-d');

    // OM TAXIs
    $ordres_taxi = \App\OMTaxi::where('CL_heuredateRDV', '>', Carbon::now()->toDateString())
       ->where('dernier',1)
        ->select('id','CL_heuredateRDV','affectea','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type','lchauff','lvehicule')
        ->orderBy('CL_heuredateRDV')
        ->get();

    //OM Ambul

    $ordres_ambul =   \App\OMAmbulance::where('CL_heuredateRDV',  '>', Carbon::now()->toDateString())
        ->where('dernier',1)
            ->select('id','CL_heuredateRDV','affectea','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type','lvehicule','lambulancier1')
        ->orderBy('CL_heuredateRDV')
         ->get();
    // OM Remorq

    $ordres_rem =  \App\OMRemorquage::where('CL_heuredateRDV',  '>', Carbon::now()->toDateString())
        ->where('dernier',1)
            ->select('id','CL_heuredateRDV','affectea','emplacement','reference_medic','subscriber_name','subscriber_lastname','CL_heure_RDV','CL_contacttel','CL_lieuprest_pc','CL_lieudecharge_dec','type','lchauff','lvehicule')
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

                  <li class="nav-item">
                      <a class="nav-link " href="{{ route('transport') }}" >
                          <i class="fas fa-2x fa-calendar-day"></i> Jour v
                      </a>
                  </li>

                  <li class="nav-item ">
                      <a class="nav-link  "  href="{{ route('transport2') }}"      >
                          <i class="fas fa-2x fa-calendar-day"></i> Jour h
                      </a>
                  </li>

                  <li class="nav-item ">
                      <a class="nav-link  " href="{{ route('transportsemaine') }}" >
                          <i class="fas fa-2x fa-calendar-week"></i>  Semaine
                      </a>
                  </li>

                  <li class="nav-item active">
                      <a class="nav-link active " href="#">
                          <i class="fas fa-2x fa-list"></i>  Tous
                      </a>
                  </li>

              </ul>
              <h1> Ordres de Missions à venir </h1>
                       <div   >
                           <div   style="border-right:2px solid #4fc1e9;min-height: 550px">

                               <?php

                               $color='';$icon='';
                                                      foreach($oms as $o)
                                                      {
                                                        $emp=$o['emplacement'];  $emppos=strpos($emp, '/OrdreMissions/'); $empsub=substr($emp, $emppos);
                                                        $date=$o['CL_heuredateRDV'];
                                                          $ref=$o['reference_medic'];
                                                          $benef=$o['subscriber_name'].' '.$o['subscriber_lastname'];
                                                          $heureT=$o['CL_heuredateRDV'];$heure= substr($heureT,11,5);  $hour=intval(substr($heureT,11,3));$dateom=substr($heureT,0,10); $dateom= date('d/m/Y', strtotime($dateom));
                                                          $tel=$o['CL_contacttel'];
                                                          $de=$o['CL_lieuprest_pc'];
                                                          $vers=$o['CL_lieudecharge_dec'];
                                                          $type=$o['type'];
                                                           $veh=$o['lvehicule'];

                                                          $affecte=$o['affectea'];if($affecte=='externe'){$color2='#0B5345';}else{$color2='#6E2C00';}
                                                         if($type=='taxi'){$chauff=$o['lchauff']; $color='#D4AC0D';$icon='<i class="fas fa-2x fa-taxi"></i>';}
                                                          if($type=='ambulance'){ $chauff=$o['lambulancier1']; $color='#2874A6';$icon='<i class="fas fa-2x fa-ambulance"></i>';}
                                                          if($type=='remorquage'){ $chauff=$o['lchauff']; $color='#C0392B';$icon='<i class="fas fa-2x fa-truck-pickup"></i>';}

                                                          ?>
                                                <div  class="om " style=" float:left;margin-top:15px;margin-right:30px;background-color:<?php echo $color2; ?>; color:white;  ;border-radius: 20px;padding:5px 5px 5px 5px;min-height: 120px"  id="div-<?php echo $type;?>-<?php echo $o['id'];?>"  ondblclick="display(this);">

                                                    <div class="row" style="padding:3px 3px 3px 3px; margin-bottom:5px;background-color:<?php echo $color; ?>; ">
                                                              <div class="col-md-9"><i class="fa fa-folder"></i> <?php echo $ref; ?></div>
                                                              <div class="col-md-3"><?php echo $icon; ?></div>
                                                          </div>

                                                    <div class="row" style= "margin-bottom:5px">
                                                        <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-calendar"></i> <?php echo $dateom; ?></div>
                                                    </div>
                                                    <div class="row" style= "margin-bottom:5px">
                                                        <div   style="background-color:white;color:black;text-align: center;font-size: 20px"><i class="fas fa-clock"></i> <?php echo $heure; ?></div>
                                                    </div>
                                                    <div class="row" style= "margin-bottom:5px">
                                                        <div   style="background-color:black;color:white;text-align: center;font-size: 20px"><a href="#" onclick="modalattach('<?php echo 'OM '.ucwords($type).' '.$ref.' '.$benef;?>','<?php echo URL::asset('storage'.$empsub);?>')"><i class="fas fa-file-alt"></i> Ouvrir </a></div>
                                                    </div>
                                                          <div id="om-<?php echo $type;?>-<?php echo $o['id'];?>"  style="display:none">
                                                          <div class="row" style="margin-bottom:10px">
                                                              <div class="col-md-12 "  ><i class="fas fa-portrait"></i>  <?php echo $benef; ?></div>
                                                              <div class="col-md-12 "  ><i class="fas fa-mobile-alt"></i>   <?php echo $tel; ?></div>
                                                          </div>
							<?php if($affecte!=='externe'){ ?>
                                                              <div class="row" style="margin-bottom:10px">
                                                                  <div class="col-md-12 overme"  ><i class="fas fa-user-alt"></i>  <?php echo $chauff; ?></div>
                                                                  <div class="col-md-12 overme "  ><i class="fas fa-car"></i>   <?php echo $veh; ?></div>
                                                              </div>
							<?php } ?>
                                                          <div class="row"  >
                                                              <div class="col-md-12"><i class="fas fa-map-marker-alt"></i> <small>De :</small>  <?php echo $de; ?> </div>
                                                          </div>

                                                          <div class="row"  >
                                                              <div class="col-md-12"><i class="fas fa-road"></i> <small>Vers:</small> <?php echo $vers; ?></div>
                                                          </div>
                                                          </div>

                                                  </div>

                                            <?php  }   ?>

                           </div>

                       </div>








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

    function modalattach(titre,emplacement)
    {
        $("#attTitle").text(titre);


        document.getElementById('attachiframe').src =emplacement;
        document.getElementById('attachiframe').style.display='block';

        $("#openattach").modal('show');
    }

</script>
