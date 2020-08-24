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

      <ul id="tabs" class="nav  nav-tabs"  >
                <li class=" nav-item ">
                    <a class="nav-link    " href="{{ route('supervision') }}"  >
                        <i class="fas fa-lg  fa-users-cog"></i>  Supervision
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('affectation') }}"  >
                        <i class="fas fa-lg  fa-user-tag"></i>  Affectations
                    </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link " href="{{ route('missions') }}"  >
                        <i class="fas fa-lg  fa-user-cog"></i>  Missions
                    </a>
                </li>
                 <li class="nav-item active">
                    <a class="nav-link" href="{{ route('Calendriermissions7') }}"  >
                        <i class="fas fa-lg  fa-user-cog"></i>  Calendrier Missions
                    </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link " href="{{ route('notifs') }}"  >
                        <i class="fa fa-lg  fa-inbox"></i>  Flux de réception
                    </a>
                </li>
       </ul>

       <!-- version affichage semaine par colonne-->

<?php $month = date('m');
    $year = date('Y');
    $date = date('d');
    $fmt = new IntlDateFormatter('fr_FR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'Europe/Paris',
        IntlDateFormatter::GREGORIAN);
    //  $date=date('l A d/m/Y');
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
    //dd($d7);
    $today= date('Y-m-d');
    //dd($today->format('Y-m-d H:i:s'));
     $format="Y-m-d H:i:s";
     $dtc = (new \DateTime())->format('Y-m-d H:i:s');
     $datesys=\DateTime::createFromFormat($format, $dtc );
    // dd($datesys->format('Y-m-d'));
                      //$datedeb = \DateTime::createFromFormat($format, $dtc);
                      //$datefin = \DateTime::createFromFormat($format, $dtc)->modify('+ 7 days');
                      //$datesys=\DateTime::createFromFormat($format, $dtc );
    ?>

   <div class="row seven-cols" style="height:1200px">
                           <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2 style="background-color: #4fc1e9!important;font-weight: 400"> <?php echo $sd1; ?> </h2> </center>
                            <br>
                            <div  style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"> 
                               <center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 1(08:00 => 15:00)</h3> </center>

                          @foreach ($jour1_seance1 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                            </div>
                            <br>
                            <div style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"><center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 2 (15:00 => 23:00)</h3> </center>

                          @foreach ($jour1_seance2 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach
                             </div>
                            <br>
                            <div  style="border:solid; border-right:2px solid #4fc1e9;min-height: 100px">
                                <center><h3 style="background-color: #4fc1e9!important;font-weight: 400">  Séance 3 (23:00 => 08:00)</h3> </center>

                          @foreach ($jour1_seance3 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                           </div>

                            <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo $sd2; ?> </h2></center>
                                <br>
                            <div style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"> 
                               <center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 1(08:00 => 15:00)</h3> </center>

                          @foreach ($jour2_seance1 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach
                            </div>
                            <br>
                            <div  style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"><center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 2 (15:00 => 23:00)</h3> </center>

                          @foreach ($jour2_seance2 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            <br>
                            <div  style="border:solid; border-right:2px solid #4fc1e9;min-height: 100px">
                                <center><h3 style="background-color: #4fc1e9!important;font-weight: 400">  Séance 3 (23:00 => 08:00)</h3> </center>

                          @foreach ($jour2_seance3 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            </div>
                            <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo $sd3; ?> </h2></center>
                                <br>
                            <div  style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"> 
                               <center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 1(08:00 => 15:00)</h3> </center>

                          @foreach ($jour3_seance1 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                            </div>
                            <br>
                            <div  style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"><center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 2 (15:00 => 23:00)</h3> </center>

                          @foreach ($jour3_seance2 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            <br>
                            <div  style="border:solid; border-right:2px solid #4fc1e9;min-height: 100px">
                                <center><h3 style="background-color: #4fc1e9!important;font-weight: 400">  Séance 3 (23:00 => 08:00)</h3> </center>

                          @foreach ($jour3_seance3 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            </div>
                            <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo $sd4; ?> </h2></center>
                                <br>
                            <div  style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"> 
                               <center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 1(08:00 => 15:00)</h3> </center>

                          @foreach ($jour4_seance1 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach
                            </div>
                            <br>
                            <div  style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"><center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 2 (15:00 => 23:00)</h3> </center>

                           @foreach ($jour4_seance2 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            <br>
                            <div  style="border:solid; border-right:2px solid #4fc1e9;min-height: 100px">
                                <center><h3 style="background-color: #4fc1e9!important;font-weight: 400">  Séance 3 (23:00 => 08:00)</h3> </center>

                          @foreach ($jour4_seance3 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            </div>
                            <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo $sd5; ?> </h2></center>
                                <br>
                            <div  style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"> 
                               <center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 1(08:00 => 15:00)</h3> </center>

                          @foreach ($jour5_seance1 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach
                            </div>
                            <br>
                            <div style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"><center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 2 (15:00 => 23:00)</h3> </center>

                          @foreach ($jour5_seance2 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            <br>
                            <div  style="border:solid; border-right:2px solid #4fc1e9;min-height: 100px">
                                <center><h3 style="background-color: #4fc1e9!important;font-weight: 400">  Séance 3 (23:00 => 08:00)</h3> </center>

                          @foreach ($jour5_seance3 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            </div>
                            <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo $sd6; ?> </h2></center>
                                <br>
                            <div  style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"> 
                               <center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 1(08:00 => 15:00)</h3> </center>

                             @foreach ($jour6_seance1 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach
                            </div>
                            <br>
                            <div style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"><center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 2 (15:00 => 23:00)</h3> </center>

                          @foreach ($jour6_seance2 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            <br>
                            <div  style="border:solid; border-right:2px solid #4fc1e9;min-height: 100px">
                                <center><h3 style="background-color: #4fc1e9!important;font-weight: 400">  Séance 3 (23:00 => 08:00)</h3> </center>

                          @foreach ($jour6_seance3 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            </div>
                            <div class="col-md-1"  style="border-right:2px solid #4fc1e9;min-height: 550px"><center><h2> <?php echo $sd7; ?> </h2></center>
                                <br>
                            <div  style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"> 
                               <center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 1(08:00 => 15:00)</h3> </center>

                          @foreach ($jour7_seance1 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach
                            </div>
                            <br>
                            <div  style="border:solid;border-right:2px solid #4fc1e9;min-height: 100px"><center><h3 style="background-color: #4fc1e9!important;font-weight: 400"> Séance 2 (15:00 => 23:00)</h3> </center>

                          @foreach ($jour7_seance2 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            <br>
                            <div  style="border:solid; border-right:2px solid #4fc1e9;min-height: 100px">
                                <center><h3 style="background-color: #4fc1e9!important;font-weight: 400">  Séance 3 (23:00 => 08:00)</h3> </center>

                          @foreach ($jour7_seance3 as $do ) 
                               @if($do->statut_courant!="endormie")
                           
                               <div style="border: ridge; border-color: grey; background-color:#ccffff ">
                               <small>{{$do->titre}}</small><br>
                               <small>{{$do->nom_type_miss}}</small><br>
                            <a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic ; ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}"> Fiche</a>
                           </div>
                        
                           <br>
                              @endif
                              @endforeach

                             </div>
                            </div>

     </div>



     <!-- Fin version affichage semaine par colonne-->

       <div class="uper">
        <div class="portlet box grey">
             <div class="row">
                <div class="col-lg-8"> <h4>Missions disponibles Pour Les prochains 7 jours </h4></div>
                <div class="col-lg-4">
                   <!-- <button id="addfolder" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createfolder"><b><i class="fas fa-folder-plus"></i> Créer un Dossier</b></button>-->
             

                </div>
            </div>
        </div>

        <!-- debut recherche avancee sur dossiers-->


       <?php
           $format = "Y-m-d H:i:s";

                     $data=App\Mission::get();

                     $datasearch=array();


                      $dtc = (new \DateTime())->format('Y-m-d H:i:s');
                      $datedeb = \DateTime::createFromFormat($format, $dtc);
                      $datefin = \DateTime::createFromFormat($format, $dtc)->modify('+ 7 days');
                      $datesys=\DateTime::createFromFormat($format, $dtc );

                      //dd($datefin);

                     
                     foreach ( $data as $d) {


                      $datecreation = \DateTime::createFromFormat($format, $d->date_deb);

               
                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                            if ($datesys>=$datedeb && $datesys<=$datefin && ($d->statut_courant=="active" || $d->statut_courant=="deleguee" ))
                            {
                               $datasearch[]=$d;
                            }


                          }
                          $actions=App\ActionEC::where('mission_id',$d->id)->get();
                          foreach ( $actions as $aa) {

                            if($aa->statut!='faite' && $aa->statut!='rfaite' && $aa->statut!='inactive' )
                            {

                              if($aa->statut=='reportee')
                              {
                              $datedeba = \DateTime::createFromFormat($format, $aa->date_report);

                          if($datedeba>= $datedeb &&  $datedeba <= $datefin)
                            {

                            $datasearch[]=$d;

                            }
                          }

                           if($aa->statut=='rappelee')
                              {
                               // dd('ok');
                              $datedeba = \DateTime::createFromFormat($format, $aa->date_rappel);

                          if($datedeba>= $datedeb &&  $datedeba <= $datefin)
                            {

                            $datasearch[]=$d;

                            }
                          }




                          if($aa->statut=='active') // active
                          {

                            if($datesys>=$datedeb && $datesys<=$datefin)
                            {
                               $datasearch[]=$d;

                            }



                          }


                            }



                          }


                     }


      $missions=$datasearch;
      $missions=array_unique($missions);

       ?>


            <table class="table table-striped" id="mytable" style="width:100%">
            <thead >
             <tr id="headtable">
                <th style="width:20%">Type Mission</th>
                <th style="width:20%">Extrait</th>
                 <th style="width:25%">Dossier</th>
                 <th style="width:20%">Séance</th>
                 <th style="width:20%">Statut </th>
                 <th style="width:20%">Date </th>
              </tr>
            <tr>
                <th style="width:20%">Type Mission</th>
                <th style="width:20%">Extrait</th>
                 <th style="width:25%">Dossier</th>
                 <th style="width:20%">Séance</th>
                  <th style="width:20%">Statut</th>

                 <th style="width:20%">Date</th>
            </tr>
            </thead>
            <tbody>

             <?php $missions=App\Mission::orderBy('created_at', 'desc')->get(); ?>
            @if($missions)

            @foreach($missions as $do)

            @if($do->statut_courant!="endormie")
                <tr>
                    <td style="width:20%"><?php echo '<small>'.$do->nom_type_miss.'</small>';?></td>
                    <td style="width:25%">
                        <?php echo '<small>'.$do->titre .'</small>';?>
                    </td>
                    <td style="width:20%"><a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic  ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}" >Fiche<i class="fa fa-file-txt"/></a></td>
                    <?php  //$deb_seance_1=(new \DateTime())->format('08:00:00');
                             $deb_seance_1=strtotime('08:00:00');
                             $fin_seance_1= strtotime('14:59:00');
                            // dd($deb_seance_1.' '.$fin_seance_1);
                         // $deb_seance_1= \Date("H:i:s",strtotime('Y-m-d 08:00:00'));
                         //dd($deb_seance_1);
                            //$fin_seance_1=(new \DateTime())->format('Y-m-d 15:00:00');

                            $deb_seance_2=strtotime('15:00:00');
                            $fin_seance_2=strtotime('22:59:00');

                           // $deb_seance_3=(new \DateTime())->format('Y-m-d 23:00:00');
                            //$fin_seance_3=(new \DateTime())->modify('+1 day')->format('Y-m-d 08:00:00');
 
                            //$format = "H:i:s";
                               // dd(strtotime($do->date_deb));
                           // $dateMiss = \DateTime::createFromFormat($format,$do->date_deb); 
                            $dateMiss =\Date("H:i:s",strtotime($do->date_deb));
                            $dateMiss=strtotime($dateMiss);
                           // dd($dateMiss);

                            if($do->statut_courant=="reportee") {

                                    if($dateMiss>=$deb_seance_1 &&  $dateMiss <=$fin_seance_1 ) { 
                                           echo '<td style="width:20%"> séance 1</td>';

                                             }elseif ($dateMiss>=$deb_seance_2 &&  $dateMiss <=$fin_seance_2 ){ 

                                                echo '<td style="width:20%"> séance 2</td>';

                                               }else { // seance 3

                                                 echo '<td style="width:20%"> séance 3</td>';

                                              } 

                                          }else // active ou deleguee ou deleguee-endormie
                                          {
                                            echo '<td style="width:20% ;color:red;"> Maintenant</td>';

                                          }


                                              ?>

                     <td style="width:20%">
                      
                      @if($do->statut_courant=="deleguee")
                      {{"déléguée"}}
                      @endif
                      @if($do->statut_courant=="reportee")
                      {{"reportée"}}
                      @endif
                       @if($do->statut_courant=="active")
                      {{"active"}}
                      @endif
                      @if($do->statut_courant=="endormie")
                      {{"endormie"}}
                      @endif

                    </td>

 
                    <td style="width:20%"> 

                     {{$do->date_deb}}                    

                    </td>



                </tr>

                @else {{-- Cas endormie--}}
                <?php $actions=App\ActionEC::where('mission_id',$do->id)->where('statut','reportee')->orWhere('statut','rappelee')->get(); ?>
                 @foreach($actions as $aa) {{--  debut action--}}

                     <tr>
                    <td style="width:20%"><?php echo '<small>'.$do->nom_type_miss.'</small>';?></td>
                    <td style="width:25%">
                        <?php echo '<small>'.$do->titre .'</small>';?>
                    </td>
                    <td style="width:20%"><a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic  ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}" >Fiche<i class="fa fa-file-txt"/></a></td>
                    <?php  //$deb_seance_1=(new \DateTime())->format('08:00:00');
                             $deb_seance_1=strtotime('08:00:00');
                             $fin_seance_1= strtotime('14:59:00');
                            // dd($deb_seance_1.' '.$fin_seance_1);
                         // $deb_seance_1= \Date("H:i:s",strtotime('Y-m-d 08:00:00'));
                         //dd($deb_seance_1);
                            //$fin_seance_1=(new \DateTime())->format('Y-m-d 15:00:00');

                            $deb_seance_2=strtotime('15:00:00');
                            $fin_seance_2=strtotime('22:59:00');

                           // $deb_seance_3=(new \DateTime())->format('Y-m-d 23:00:00');
                            //$fin_seance_3=(new \DateTime())->modify('+1 day')->format('Y-m-d 08:00:00');
 
                            //$format = "H:i:s";
                               // dd(strtotime($do->date_deb));
                           // $dateMiss = \DateTime::createFromFormat($format,$do->date_deb); 
                            if($aa->statut=="reportee")
                                {$tt=$aa->date_report ;}
                            else{if($aa->statut=="rappelee")
                                {$tt=$aa->date_rappel ;}}
                            $dateMiss =\Date("H:i:s",strtotime($tt));
                            $dateMiss=strtotime($dateMiss);
                           // dd($dateMiss);


                                    if($dateMiss>=$deb_seance_1 &&  $dateMiss <=$fin_seance_1 ) { 
                                           echo '<td style="width:20%"> séance 1</td>';

                                             }elseif ($dateMiss>=$deb_seance_2 &&  $dateMiss <=$fin_seance_2 ){ 

                                                echo '<td style="width:20%"> séance 2</td>';

                                               }else { // seance 3

                                                 echo '<td style="width:20%"> séance 3</td>';

                                              } ?>

                     <td style="width:20%">
                                           
                      {{"endormie"}}
                     

                    </td>

 
                    <td style="width:20%"> 

                     <?php if($aa->statut=="reportee"){echo $aa->date_report ;}else{if($aa->statut=="rappelee"){echo $aa->date_rappel ;}} ?>                    

                    </td>



                </tr>



                 @endforeach {{--  fin action--}}
                @endif
            @endforeach
            @endif
            </tbody>
        </table>

            <!-- fin recherche avancee sur dossiers-->

         
    </div>

<style>
     h2{background-color: grey;color:white;height: 40px;padding-top:5px;}
        h2 small{color:#FCFBFB;}

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

      /***class om***/

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
    </style>

 @endsection
 