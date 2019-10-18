<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Action;
use App\Mission;
use App\Dossier;
use App\TypeMission;
use App\User;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Routing\UrlGenerator;
use URL;

class RechercheController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function test (Request $request)
    {
      $qery=$request->get('qy');
     // dd($qery);
      $dossiertechs=DB::Table('dossiers')->whereNotNull('vehicule_immatriculation')->get();
     // dd($dossiertechs);
           $collectDossierTech = collect();
           //$c->add(new Post);
           foreach ($dossiertechs as $dt) {

                  $immatavecCar=trim($dt->vehicule_immatriculation);
                  //dd($immatavecCar);

                  if(strpos($qery,"-")!== false || strpos($qery,"_")!== false || strpos($qery,"(")!== false || strpos($qery,")")!== false)
                  {
                     $immatSanscarS= $immatavecCar;
                    // dd("aya");

                  }
                  else
                  {

                     $immatSanscarS = preg_replace("#[^1-9a-zA-Z]#","", $immatavecCar);
                     //dd("ooo");
                  }
                 
                 //dd($immatSanscarS);
                  if( stristr($immatSanscarS,$qery)!=false )
                  {

                    //dd('bonjour');

                       $collectDossierTech->push($dt);

                  }

             // dd($collectDossierTech);

           }

            foreach ( $collectDossierTech as $row ) {

              dd($row->id);

            }

           return back();


    }



      public function rechercheMultiAjax(Request $request)
    {

       $contenu=false;
       $burl = URL::to("/");

        if($request->get('qy'))
        {
          $qery=$request->get('qy');
          $output='<ul class="dropdown-menu kbsdropdowns" style="height: 250px; overflow-y: auto; width:550;" >';

 //--------------------------recherche depuis la table Dossier----------------------------------------------


    //--recherhe  des dossiers selon la reférence médic (référence selon la société) --

           $data=DB::Table('dossiers')->where('reference_medic','like','%'.$qery.'%')->limit(10)->get();

         
          if(count($data)!=0)
           {
            $output.='<li class="divider"></li>';
            $contenu=true;
          foreach ($data as $row ) {

             if($row->affecte)
            {
              $use=User::where('id',$row->affecte )->first();         
              $affecOuNon=" ( Affecté à ". $use->name." ".$use->lastname." )";

            }
            else
            {
               $affecOuNon=" ( Non Affecté ) ";
            }
              
             // $urln= URL::to('/');
              $output.='<li  class="resAutocompRech" style=" align: left; width:500px; left:-50px;"  ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->reference_medic.' '.$row->subscriber_name.' '.$row->subscriber_lastname.'( Réf. Médicale)'. $affecOuNon.'</a></li>';
          }

          $output.='<li class="divider"></li>';
           }

    // recherche  des dossiers selon la reférence client (référence selon la société) --
 
         $data=DB::Table('dossiers')->where('reference_customer','like','%'.$qery.'%')->limit(10)->get();

         
          if(count($data)!=0)
           {
            $output.='<li class="divider"></li>';
            $contenu=true;
          foreach ($data as $row ) {

            if($row->affecte)
            {
              $use=User::where('id',$row->affecte )->first();         
              $affecOuNon=" ( Affecté à ". $use->name." ".$use->lastname." )";

            }
            else
            {
               $affecOuNon=" ( Non Affecté ) ";
            }
             // $urln= URL::to('/');
              $output.='<li  class="resAutocompRech" style=" align: left; width:500px; overflow: hidden; left:-50px;"  ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->reference_customer.' (Dossier selon la Réf client) '.$affecOuNon.'</a></li>';
          }

          $output.='<li class="divider"></li>';
           }

    //-recherhe sur le nom et le prénom de l'abonnée 

           $data=DB::Table('dossiers')->where('subscriber_name','like','%'.$qery.'%')->orWhere('subscriber_lastname','like','%'.$qery.'%')->limit(10)->get();

        //  $output='<div><ul class="dropdown-menu " style="display:block ; position:relative ; top:-65px; ">';
          if(count($data)!=0)
          {
            $output.='<li class="divider"></li>';
          $contenu=true;
          foreach ($data as $row ) {

            if($row->affecte)
            {
              $use=User::where('id',$row->affecte )->first();         
              $affecOuNon=" ( Affecté à ". $use->name." ".$use->lastname." )";

            }
            else
            {
               $affecOuNon=" ( Non Affecté ) ";
            }
              
              $output.='<li  class="resAutocompRech" style=" align: left; width:500px; overflow: hidden;left:-50px; margin-right:7px;" ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->subscriber_name.'  '. $row->subscriber_lastname.'  (Dossier '.$row->reference_medic.') '.$affecOuNon.'</a></li>';
          }

          $output.='<li class="divider"></li>';
         }


         //-recherhe sur l'immatriculation sans caractères spéciaux - _ ( )

           $dossiertechs=DB::Table('dossiers')->whereNotNull('vehicule_immatriculation')->get();
          // dd($dossiertechs);
           $collectDossierTech = collect();
           //$c->add(new Post);
           foreach ($dossiertechs as $dt) {

                  $immatavecCar=trim($dt->vehicule_immatriculation);
               

                if(strpos($qery,"-")!== false || strpos($qery,"_")!== false || strpos($qery,"(")!== false || strpos($qery,")")!== false)
                  {
                     $immatSanscarS= $immatavecCar;
                    // dd("aya");

                  }
                  else
                  {

                     $immatSanscarS = preg_replace("#[^1-9a-zA-Z]#","", $immatavecCar);
                     //dd("ooo");
                  }
                 
                  if( stristr($immatSanscarS,$qery)!=false )
                  {
                
                       $collectDossierTech->push($dt);

                  }

       

           }




          if(count( $collectDossierTech)!=0)
          {
            $output.='<li class="divider"></li>';
          $contenu=true;
          foreach ( $collectDossierTech as $row ) {
            
            if($row->affecte)
            {
              $use=User::where('id',$row->affecte )->first();         
              $affecOuNon=" ( Affecté à ". $use->name." ".$use->lastname." )";

            }
            else
            {
               $affecOuNon=" ( Non Affecté ) ";
            }

              $output.='<li  class="resAutocompRech" style=" align: left; width:500px; overflow: hidden;left:-50px; margin-right:7px;" ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->vehicule_immatriculation.' (Dossier selon l\'immatriculation véhivule) '.$affecOuNon.'</a></li>';
          }

          $output.='<li class="divider"></li>';
         }



           $output.='</ul>';

          


        }

      if($contenu==true)
           {
           
           echo  $output ;
           }
           else
           {
            echo "<br>";
           }


    }


public function pageRechercheAvancee(Request $request )
{

 //dd($request->all());

      /*"reference_medic1" => "15TM0004"
  "current_status" => "0"
  "customer_id_search" => "0"
  "nom_benef_search" => null
  "pres_id_search" => null*/


  /* $format = "Y-m-d H:i:s";
          $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));
          dd($datedeb);

         // dd($datefin);
          $datasearch=Dossier::where('reference_medic',$request->get('reference_medic1'))->first();


           dd($datecreation);*/

            $datasearch =null;

           $format = "Y-m-d H:i:s";

  if($request->get('reference_medic1'))

  {


                    $datasearch=Dossier::where('reference_medic',$request->get('reference_medic1'))->get();



      // dd( $datasearch);

      // return redirect()->back()->with(compact('datasearch'));


  }

  else
  {


           if($request->get('reference_medic1')==null && $request->get('current_status')== null && $request->get('customer_id_search') == null && $request->get('nom_benef_search') == null &&  $request->get('pres_id_search') == null )
            {

                if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                  {
                     //dd('ok');


                     $data=Dossier::get();

                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);
//

                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                  }


            }
           // else
           // {
              // dd('ok');
              //------------------- 1/4-----------------------------------------

               if($request->get('current_status')  && $request->get('customer_id_search')==null && $request->get('nom_benef_search') == null && $request->get('pres_id_search')== null)

              {



                   if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('ok');


                     $data=Dossier::where('current_status',$request->get('current_status'))->get();

                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                  }
                  else
                  {
                    //dd("Pas de date");

                   $datasearch=Dossier::where('current_status',$request->get('current_status'))->get();


                   }



              }

              if($request->get('current_status') && $request->get('customer_id_search') && $request->get('nom_benef_search') == null &&  $request->get('pres_id_search')== null)

              {


              if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okb');



                     $data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();

                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                  }
                  else
                  {
                     // dd('okb');

                   $datasearch=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();

                  }
              }


               if($request->get('current_status') && $request->get('customer_id_search')  && $request->get('nom_benef_search') != null &&  $request->get('pres_id_search')== null)

              {


                   if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okbr');



                     $da=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();

                         $data = array();

                            foreach($da as $d )
                            {
                                     $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                      if(stripos( $c,$request->get('nom_benef_search')) )
                                            {
                                                 $data[]=$d;
                                            }

                            }



                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                  }
                  else
                  {


                 //dd('kkk');

                  $data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();

                         $datasearch = array();

                            foreach($data as $d )
                            {
                                     $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                      if(stripos( $c,$request->get('nom_benef_search')) )
                                            {
                                                 $datasearch[]=$d;
                                            }

                            }

                   }

              }


               if($request->get('current_status') && $request->get('customer_id_search') && $request->get('nom_benef_search')  &&  $request->get('pres_id_search') != null)
                {

                 // dd("ok");

                  /*$data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();*/

                     if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                    // dd('okbr');



                    $da = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();


                       $data = array();

                            foreach($da as $d )
                            {
                                     $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                      if(stripos( $c,$request->get('nom_benef_search')) )
                                            {
                                                 $data[]=$d;
                                            }

                            }




                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                  }

                  else
                  {

                    // dd('okkk');

                     $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();


                       $datasearch = array();

                            foreach($data as $d )
                            {
                                     $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                      if(stripos( $c,$request->get('nom_benef_search')) )
                                            {
                                                 $datasearch[]=$d;
                                            }

                            }
                      }

                 // dd( $datasearch);

                  }

                   if($request->get('current_status') && $request->get('customer_id_search') == null && $request->get('nom_benef_search') != null && $request->get('pres_id_search')== null)

                      {

                    if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okbr');


                      $da=Dossier::where('current_status',$request->get('current_status'))->get();

                      $data = array();

                            foreach($da as $d )
                                    {
                                             $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                              if(stripos( $c,$request->get('nom_benef_search')) )
                                                    {
                                                         $data[]=$d;
                                                    }

                                    }




                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                   //  dd($datasearch);

                  }


                        else
                        {
                            //dd('fffff');

                          $data=Dossier::where('current_status',$request->get('current_status'))->get();

                                 $datasearch = array();

                                    foreach($data as $d )
                                    {
                                             $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                              if(stripos( $c,$request->get('nom_benef_search')) )
                                                    {
                                                         $datasearch[]=$d;
                                                    }

                                    }




                          }




                      }



                    if($request->get('current_status') && $request->get('customer_id_search')==null && $request->get('nom_benef_search')==null  &&  $request->get('pres_id_search') != null)
                {

                 // dd("ok");

                  /*$data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();*/
                       if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okbr');


                       $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();




                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                   //  dd($datasearch);

                  }



                    else
                    {

                     $datasearch = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();

                    }
                 // dd( $datasearch);


                }

                //-----------------------------2/4--------------------------------------------

                   if($request->get('current_status')==null && $request->get('customer_id_search') && $request->get('nom_benef_search') == null &&  $request->get('pres_id_search')== null)

              {

                  if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okbr');


                       $data=Dossier::where('customer_id',$request->get('customer_id_search'))->get();




                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                   //  dd($datasearch);

                  }




                else
                {

                   $datasearch=Dossier::where('customer_id',$request->get('customer_id_search'))->get();


                 }

              }



               if($request->get('current_status')==null && $request->get('customer_id_search') && $request->get('nom_benef_search') &&  $request->get('pres_id_search')== null)

              {

                     if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okbr');


                        $da=Dossier::where('customer_id',$request->get('customer_id_search'))->get();

                         $data = array();

                            foreach($da as $d )
                            {
                                     $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                      if(stripos( $c,$request->get('nom_benef_search')) )
                                            {
                                                 $data[]=$d;
                                            }

                            }




                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                   //  dd($datasearch);

                  }


                else
                {

                 $data=Dossier::where('customer_id',$request->get('customer_id_search'))->get();

                         $datasearch = array();

                            foreach($data as $d )
                            {
                                     $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                      if(stripos( $c,$request->get('nom_benef_search')) )
                                            {
                                                 $datasearch[]=$d;
                                            }

                            }

                   }
              }


                if($request->get('current_status')== null && $request->get('customer_id_search') && $request->get('nom_benef_search') == null &&  $request->get('pres_id_search') != null)
                {


                  if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okbr');


                        $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();




                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                   //  dd($datasearch);

                  }




                    else
                    {
                     $datasearch = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();


                    }


                 // dd( $datasearch);

                  }


                   if($request->get('current_status')==null && $request->get('customer_id_search') && $request->get('nom_benef_search')  &&  $request->get('pres_id_search') != null)
                {

                 // dd("ok");

                  /*$data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();*/

                     if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                    // dd('okbr');



                    $da = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();


                       $data = array();

                            foreach($da as $d )
                            {
                                     $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                      if(stripos( $c,$request->get('nom_benef_search')) )
                                            {
                                                 $data[]=$d;
                                            }

                            }




                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                  }

                  else
                  {

                    // dd('okkk');

                     $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();


                       $datasearch = array();

                            foreach($data as $d )
                            {
                                     $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                      if(stripos( $c,$request->get('nom_benef_search')) )
                                            {
                                                 $datasearch[]=$d;
                                            }

                            }
                      }

                 // dd( $datasearch);

                  }




                //----------------------------------------------3/4--------------------------------------------------


                  if($request->get('current_status')==null && $request->get('customer_id_search')==null && $request->get('nom_benef_search') &&  $request->get('pres_id_search')== null)

                      {

                        //dd('3-4');

                             if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okbr');


                       $da=Dossier::get();

                                 $data = array();

                                    foreach($da as $d )
                                    {
                                             $c= " ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                              if(stripos( $c,$request->get('nom_benef_search')) )
                                                    {
                                                      //dd("ok");

                                                         $data[]=$d;
                                                    }

                                    }




                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);

                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                   //  dd($datasearch);

                  }


                        else
                        {

                         $data=Dossier::get();

                                 $datasearch = array();

                                    foreach($data as $d )
                                    {
                                             $c= " ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                              if(stripos( $c,$request->get('nom_benef_search')) )
                                                    {
                                                      //dd("ok");

                                                         $datasearch[]=$d;
                                                    }

                                    }

                          }


                      }

                 if($request->get('current_status')==null && $request->get('customer_id_search')==null && $request->get('nom_benef_search')  &&  $request->get('pres_id_search') != null)
                {



                           if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okbr');


                       $da = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();


                       $data = array();

                            foreach($da as $d )
                            {
                                     $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                      if(stripos( $c,$request->get('nom_benef_search')) )
                                            {
                                                 $data[]=$d;
                                            }

                            }




                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);


                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                   //  dd($datasearch);

                  }


                  else
                  {

                           //dd('jjj');
                     $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();


                       $datasearch = array();

                            foreach($data as $d )
                            {
                                     $c=" ".$d->subscriber_name." ".$d->subscriber_lastname." ".$d->vehicule_immatriculation;
                                      if(stripos( $c,$request->get('nom_benef_search')) )
                                            {
                                                 $datasearch[]=$d;
                                            }

                            }


                 // dd( $datasearch);
                    }

                  }



              //--------------------------------------------------4/4---------------------------------------------

                   if($request->get('current_status')==null && $request->get('customer_id_search')==null && $request->get('nom_benef_search')==null  &&  $request->get('pres_id_search') != null)
                {

                             if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okbr');


                       $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();




                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'));

                     foreach ( $data as $d) {



                      if($d->created !=null)
                      {

                       $datecreation = \DateTime::createFromFormat($format, $d->created);


                      }

                     else
                     {
                       //$format2 = "Y-m-d H:i:s.u";

                     // dd($d->created_at);

                      //$datecreation= Carbon::parse($d->created_at)->format($format);
                      // $datecreation= Carbon::createFromFormat($format,$d->created_at);




                      $datecreation = \DateTime::createFromFormat($format, $d->created_at);

                      // dd( $datecreation);


                     }

                          if($datecreation >= $datedeb &&  $datecreation <= $datefin)
                          {

                            $datasearch[]=$d;

                          }


                     }

                   //  dd($datasearch);

                  }



                    else
                    {

                     $datasearch = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();

                    }
                 // dd( $datasearch);

                  }






           // }
  }

    return view('dossiers.index', compact('datasearch'));

 // dd($request->all());

  // $qery=$request->get('qy');

 // return view('recherche.recherche_avancee');
}




   
}// fin controller



 
