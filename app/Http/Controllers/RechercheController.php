<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Action;
use App\Mission;
use App\Dossier;
use App\TypeMission;
use Auth;
use DB;
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
          $output='<ul class="dropdown-menu kbsdropdowns" style="height: 250px; overflow-y: auto;" >';

 //--------------------------recherche depuis la table Dossier----------------------------------------------


    //--recherhe  des dossiers selon la reférence médic (référence selon la société) --

           $data=DB::Table('dossiers')->where('reference_medic','like','%'.$qery.'%')->limit(10)->get();

         
          if(count($data)!=0)
           {
            $output.='<li class="divider"></li>';
            $contenu=true;
          foreach ($data as $row ) {
              
             // $urln= URL::to('/');
              $output.='<li  class="resAutocompRech" style=" align: left; width:400px; left:-50px;"  ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->reference_medic.' (dossier selon la Réf Médic)</a></li>';
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
              
             // $urln= URL::to('/');
              $output.='<li  class="resAutocompRech" style=" align: left; width:400px; overflow: hidden; left:-50px;"  ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->reference_customer.' (Dossier selon la Réf client)</a></li>';
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
              
              $output.='<li  class="resAutocompRech" style=" align: left; width:400px; overflow: hidden;left:-50px; margin-right:7px;" ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->subscriber_name.'  '. $row->subscriber_lastname.'  (Dossier '.$row->reference_medic.')</a></li>';
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
              
              $output.='<li  class="resAutocompRech" style=" align: left; width:400px; overflow: hidden;left:-50px; margin-right:7px;" ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->vehicule_immatriculation.' (Dossier selon l\'immatriculation véhivule)</a></li>';
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

  if($request->get('reference_medic1'))

  {

    

      $datasearch=Dossier::where('reference_medic',$request->get('reference_medic1'))->get();


      // dd( $datasearch);
     
      // return redirect()->back()->with(compact('datasearch'));


  }
  else
  {

    //------------------- 1/4-----------------------------------------
    if($request->get('current_status') && $request->get('customer_id_search')== 0 && $request->get('nom_benef_search') == null && 
      $request->get('pres_id_search')== null)

    {
         
         $datasearch=Dossier::where('current_status',$request->get('current_status'))->get();
      
    }

    if($request->get('current_status') && $request->get('customer_id_search') != 0 && $request->get('nom_benef_search') == null && 
      $request->get('pres_id_search')== null)

    {
  
         $datasearch=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get(); 
    }


     if($request->get('current_status') && $request->get('customer_id_search') != 0 && $request->get('nom_benef_search') != null && 
      $request->get('pres_id_search')== null)

    {


        $data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();

               $datasearch = array(); 

                  foreach($data as $d )
                  {              
                           $c=$d->subscriber_name." ".$d->subscriber_lastname;
                            if(stripos( $c,$request->get('nom_benef_search')) )
                                  {

                                       $datasearch[]=$d;
                                  }

                  }



    }


     if($request->get('current_status')== 0 && $request->get('customer_id_search') == 0 && $request->get('nom_benef_search') == null &&  $request->get('pres_id_search') != null)
      {

       // dd("ok");

        /*$data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();*/


           $datasearch = DB::table('prestations')
            ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
            ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
            ->where('prestataires.id','=', $request->get('pres_id_search'))        
            ->select('dossiers.*', 'prestataires.name')
            ->get();
            

       // dd( $datasearch);

        }

      //-----------------------------2/4--------------------------------------------




  }

    return view('dossiers.index', compact('datasearch'));

 // dd($request->all());

  // $qery=$request->get('qy');

 // return view('recherche.recherche_avancee');
}




   
}// fin controller



 
