<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Action;
use App\Mission;
use App\ActionEC;
use App\Dossier;
use App\TypeMission;
use App\User;
use App\Prestataire;
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
      $dossiertechs=DB::Table('dossiers')->whereNotNull('vehicule_immatriculation')->orderBy('id','desc')->get();
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


// recherhce menu global
      public function rechercheMultiAjax(Request $request)
    {

       $contenu=false;
       $affecOuNon='';
       $etat='';
       $burl = URL::to("/");
       $dtc = (new \DateTime())->format('2018-12-31 00:00:00');
        if($request->get('qy'))
        {
          $qery=$request->get('qy');
          $output='<ul class="dropdown-menu kbsdropdowns" style="height: 250px; overflow-y: auto; width:570;" >';

 //--------------------------recherche depuis la table Dossier----------------------------------------------


    //--recherhe  des dossiers selon la reférence médic (référence selon la société) --

           $data=DB::Table('dossiers')->where('reference_medic','like','%'.$qery.'%')->whereNotNull('created_at')->where('created_at','>=', $dtc)->limit(10)->orderBy('id','desc')->get();

         
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
              if($row->current_status=='Cloture')
              {
                $etat='clos';
              }
              else
              {
                 $etat=$row->current_status;
              }
             // $urln= URL::to('/');
              $output.='<li  class="resAutocompRech" style=" align: left; width:570px; left:-50px;"  ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->reference_medic.' '.$row->subscriber_name.' '.$row->subscriber_lastname.'( Réf. Médicale) ('. $etat.')'. $affecOuNon.'</a></li>';
          }

          $output.='<li class="divider"></li>';
           }

    // recherche  des dossiers selon la reférence client (référence selon la société) --
 
         $data=DB::Table('dossiers')->where('reference_customer','like','%'.$qery.'%')->whereNotNull('created_at')->where('created_at','>=', $dtc)->limit(10)->orderBy('id','desc')->get();

         
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

              if($row->current_status=='Cloture')
              {
                $etat='clos';
              }
              else
              {
                 $etat=$row->current_status;
              }

              $output.='<li  class="resAutocompRech" style=" align: left; width:570px; overflow: hidden; left:-50px;"  ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->reference_customer.' ( Réf. client) (Réf: '.$row->reference_medic.') ('.$etat.')'.$affecOuNon.'</a></li>';
          }

          $output.='<li class="divider"></li>';
           }

    //-recherhe sur le nom et le prénom de l'abonnée 

           $data=DB::Table('dossiers')->whereNotNull('created_at')->where('created_at','>=', $dtc)
                ->where(function($q) use($qery) {                             
                               $q->where('subscriber_name','like','%'.$qery.'%')->orWhere('subscriber_lastname','like','%'.$qery.'%');
                                })->limit(10)->orderBy('id','desc')->get();

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

             if($row->current_status=='Cloture')
              {
                $etat='clos';
              }
              else
              {
                 $etat=$row->current_status;
              }
              
              $output.='<li  class="resAutocompRech" style=" align: left; width:570px; overflow: hidden;left:-50px; margin-right:7px;" ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->subscriber_name.'  '. $row->subscriber_lastname.'  (Dossier '.$row->reference_medic.') ('.$etat.')'.$affecOuNon.'</a></li>';
          }

          $output.='<li class="divider"></li>';
         }


         //-recherhe sur l'immatriculation sans caractères spéciaux - _ ( )

           $dossiertechs=DB::Table('dossiers')->whereNotNull('vehicule_immatriculation')->whereNotNull('created_at')->where('created_at','>=', $dtc)->orderBy('id','desc')->get();
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

             if($row->current_status=='Cloture')
              {
                $etat='clos';
              }
              else
              {
                 $etat=$row->current_status;
              }

              $output.='<li  class="resAutocompRech" style=" align: left; width:570px; overflow: hidden;left:-50px; margin-right:7px;" ><a href="'.$burl.'/dossiers/view/'.$row->id.'">'.$row->vehicule_immatriculation.' (Dossier selon l\'immatriculation véhivule) ('.$etat.')'.$affecOuNon.'</a></li>';
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

    public function touslesprestataires(Request $request)
    {

          $prests=Prestataire::get(['id','name','civilite','prenom','ville','ville_id','annule']);

           return view('prestataires.index', compact('prests')); 

    }

    public function RechercheMissions (Request $request)
    {
      //dd($request->all());
      $format = "Y-m-d H:i:s";

      if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )
                   {

                     $data=Mission::get();

                     $datasearch=array();

                      $datedeb = \DateTime::createFromFormat($format, $request->get('date_debut'));

                      $datefin = \DateTime::createFromFormat($format, $request->get('date_fin'))->modify('+ 8 hours');
                      $dtc = (new \DateTime())->format( $format);
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
                          $actions=ActionEC::where('mission_id',$d->id)->get();
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



                   }


      $missions=$datasearch;
      $missions=array_unique($missions);


      return view('missions.calendrier', compact('missions')); 

    }

  public function RecherchePrestataireAvancee (Request $request )
   {
   //dd($request->all());

   /*  $mat=array();
     $mat[0][]=array('t1'=>1, 't2'=>2, 't5'=>array());
     $mat[0][]=array('t3'=>1, 't4'=>2);
     $mat[1][]=array();
     $mat[2][]=array();*/

     ///dd($mat);

  /*foreach ($mat as $key => $value) {
 echo $mat[$key];
   echo ' ';
  }*/
/*dd('fin');
     dd($request->all());*/

      /* $datasearch =null;
       $gouvs=  PrestatairesController::PrestataireGouvs($id);
       $typesp=  PrestatairesController::PrestataireTypesP($id);
       $specs=  PrestatairesController::PrestataireSpecs($id);
       $format = "Y-m-d H:i:s";*/

      /* array:5 [▼
  "pres_id_search" => null
  "typepres_id_search" => null
  "gouv_id_search" => null 
  "ville_id_search" => null
  "spec_id_search" => null
]*/
     // dd($request->all());
     // dd($request->get('pres_id_search').' / hidden : '.$request->get('pres_id_search_hidden'));
     if($request->get('pres_id_search_hidden'))
       {

          if(is_numeric($request->get('pres_id_search_hidden')))
          {
           $prests=Prestataire::where('id',$request->get('pres_id_search_hidden'))->get();
           //dd( $prests) ;
          }
         /* else
          {
            $prests=Prestataire::where('name','like', '%'.$request->get('pres_id_search').'%')
            ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%')->get(); 
           
          }*/

       }
       else
       {
        if($request->get('pres_id_search') && $request->get('typepres_id_search') == null && $request->get('gouv_id_search')== null && $request->get('ville_id_search')== null && $request->get('spec_id_search')==null)
        {
           $prests=Prestataire::where('name','like', '%'.$request->get('pres_id_search').'%')
            ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%')->get(); 
           
        }
        else
          {       // dd();
          // début cas 1----------------------------------------------------------------------------
        // si prstataire non identifié 1 0 0 0------------
        if ($request->get('typepres_id_search') != null && $request->get('gouv_id_search')== null && $request->get('ville_id_search')== null && $request->get('spec_id_search')==null )
            {

             $idprestataire = DB::table('prestataires_type_prestations')->where('type_prestation_id','=',$request->get('typepres_id_search'))->pluck('prestataire_id')->toArray();
              
            // $val=41; 
            //  dd(in_array($val,array_values($idprestataire)));

             $idprestataire=array_unique($idprestataire);

           if($request->get('pres_id_search'))
               {
          $prests=Prestataire::whereIn('id',array_values($idprestataire))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
              }
              else
              {
                $prests=Prestataire::whereIn('id',array_values($idprestataire))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
              }

            }


           //1 1 0 0

            if ($request->get('typepres_id_search') != null && $request->get('gouv_id_search')!= null && $request->get('ville_id_search')== null && $request->get('spec_id_search')==null )
            {
              $idprestatairetype = DB::table('prestataires_type_prestations')->where('type_prestation_id','=',$request->get('typepres_id_search'))->pluck('prestataire_id')->toArray();

                 $idprestatairegouv= DB::table('cities_prestataires')->where('citie_id',$request->get('gouv_id_search') )->pluck('prestataire_id')->toArray();

                 $result=array_intersect($idprestatairetype,$idprestatairegouv);
    
            // $val=41; 
            //  dd(in_array($val,array_values($idprestataire)));

           $result=array_unique($result);

            if($request->get('pres_id_search'))
               {
           $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
              else
              {
               $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
              }


            }

            //1 0 1 0

            if ($request->get('typepres_id_search') != null && $request->get('gouv_id_search')== null && $request->get('ville_id_search')!= null && $request->get('spec_id_search')==null )
            {
              $idprestatairetype = DB::table('prestataires_type_prestations')->where('type_prestation_id','=',$request->get('typepres_id_search'))->pluck('prestataire_id')->toArray();
              $prestatairesville = DB::table('prestataires')->where('ville','=',$request->get('ville_id_search'))->pluck('id')->toArray();

                

                 $result=array_intersect($idprestatairetype, $prestatairesville);
    
            // $val=41; 
            //  dd(in_array($val,array_values($idprestataire)));

           $result=array_unique($result);
           if($request->get('pres_id_search'))
               {
               $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }

            }

            //  1001
             if ($request->get('typepres_id_search') != null && $request->get('gouv_id_search')== null && $request->get('ville_id_search')== null && $request->get('spec_id_search')!=null )
            {
              $idprestatairetype = DB::table('prestataires_type_prestations')->where('type_prestation_id','=',$request->get('typepres_id_search'))->pluck('prestataire_id')->toArray();

                 $idprestatairespec= DB::table('specialites_prestataires')->where('specialite',$request->get('spec_id_search'))->pluck('prestataire_id')->toArray();


                 $result=array_intersect($idprestatairetype, $idprestatairespec);
    
            // $val=41; 
            //  dd(in_array($val,array_values($idprestataire)));

           $result=array_unique($result);
            if($request->get('pres_id_search'))
               {
           $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);

                }

            }

          //1101

          if ($request->get('typepres_id_search') != null && $request->get('gouv_id_search')!= null && $request->get('ville_id_search')== null && $request->get('spec_id_search')!=null )
            {
              $idprestatairetype = DB::table('prestataires_type_prestations')->where('type_prestation_id','=',$request->get('typepres_id_search'))->pluck('prestataire_id')->toArray();

                $idprestatairegouv= DB::table('cities_prestataires')->where('citie_id',$request->get('gouv_id_search') )->pluck('prestataire_id')->toArray();

                 $idprestatairespec= DB::table('specialites_prestataires')->where('specialite',$request->get('spec_id_search'))->pluck('prestataire_id')->toArray();


                 $result1=array_intersect($idprestatairetype, $idprestatairespec);
                 $result=array_intersect( $result1, $idprestatairegouv);
    
            // $val=41; 
            //  dd(in_array($val,array_values($idprestataire)));

           $result=array_unique($result);
           if($request->get('pres_id_search'))
               {
           $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {

                $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }

            }

              //1011

          if ($request->get('typepres_id_search') != null && $request->get('gouv_id_search')== null && $request->get('ville_id_search')!= null && $request->get('spec_id_search')!=null )
            {
              $idprestatairetype = DB::table('prestataires_type_prestations')->where('type_prestation_id','=',$request->get('typepres_id_search'))->pluck('prestataire_id')->toArray();

                $prestatairesville = DB::table('prestataires')->where('ville','=',$request->get('ville_id_search'))->pluck('id')->toArray();

                 $idprestatairespec= DB::table('specialites_prestataires')->where('specialite',$request->get('spec_id_search'))->pluck('prestataire_id')->toArray();


                 $result1=array_intersect($idprestatairetype, $idprestatairespec);
                 $result=array_intersect( $result1, $prestatairesville);
    
            // $val=41; 
            //  dd(in_array($val,array_values($idprestataire)));

           $result=array_unique($result);
             if($request->get('pres_id_search'))
               {
                $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                 $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
                }

            }

            // 1110

            if ($request->get('typepres_id_search') != null && $request->get('gouv_id_search')!= null && $request->get('ville_id_search')!= null && $request->get('spec_id_search')==null )
            {
              $idprestatairetype = DB::table('prestataires_type_prestations')->where('type_prestation_id','=',$request->get('typepres_id_search'))->pluck('prestataire_id')->toArray();

                $idprestatairegouv= DB::table('cities_prestataires')->where('citie_id',$request->get('gouv_id_search') )->pluck('prestataire_id')->toArray();

                $prestatairesville = DB::table('prestataires')->where('ville','=',$request->get('ville_id_search'))->pluck('id')->toArray();

               
                 $result1=array_intersect($idprestatairetype, $idprestatairegouv);
                 $result2=array_intersect($prestatairesville, $result1);
                 $result=array_intersect( $result1,$result2);
    
            // $val=41; 
            //  dd(in_array($val,array_values($idprestataire)));

           $result=array_unique($result);
           if($request->get('pres_id_search'))
               {
                $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                 $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
                }

            }

           // 1111
             if ($request->get('typepres_id_search') != null && $request->get('gouv_id_search')!= null && $request->get('ville_id_search')!= null && $request->get('spec_id_search')!=null )
            {
              $idprestatairetype = DB::table('prestataires_type_prestations')->where('type_prestation_id','=',$request->get('typepres_id_search'))->pluck('prestataire_id')->toArray();

                $idprestatairegouv= DB::table('cities_prestataires')->where('citie_id',$request->get('gouv_id_search') )->pluck('prestataire_id')->toArray();

                $prestatairesville = DB::table('prestataires')->where('ville','=',$request->get('ville_id_search'))->pluck('id')->toArray();

                 $idprestatairespec= DB::table('specialites_prestataires')->where('specialite',$request->get('spec_id_search'))->pluck('prestataire_id')->toArray();


                 $result1=array_intersect($idprestatairetype, $idprestatairegouv);
                 $result2=array_intersect($prestatairesville, $idprestatairespec);
                 $result=array_intersect( $result1,$result2);
    
            // $val=41; 
            //  dd(in_array($val,array_values($idprestataire)));

           $result=array_unique($result);
           if($request->get('pres_id_search'))
               {
                $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                 $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
                }

            }


            //-------------------------------------fin cas 1-----------------------------------------------

            // 0100

             if ($request->get('typepres_id_search') == null && $request->get('gouv_id_search')!= null && $request->get('ville_id_search')== null && $request->get('spec_id_search')==null )
            {
             
                $idprestatairegouv= DB::table('cities_prestataires')->where('citie_id',$request->get('gouv_id_search') )->pluck('prestataire_id')->toArray();
            // $val=41; 
            //  dd(in_array($val,array_values($idprestataire)));

           $idprestatairegouv=array_unique($idprestatairegouv);
           //$prests=Prestataire::whereIn('id',array_values( $idprestatairegouv))->get(['id','name','civilite','prenom','ville','ville_id']);

              if($request->get('pres_id_search'))
               {
                $prests=Prestataire::whereIn('id',array_values( $idprestatairegouv))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                 $prests=Prestataire::whereIn('id',array_values( $idprestatairegouv))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
                }

            }


            //0110


            if ($request->get('typepres_id_search') == null && $request->get('gouv_id_search')!= null && $request->get('ville_id_search')!= null && $request->get('spec_id_search')==null )
            {
             
                $idprestatairegouv= DB::table('cities_prestataires')->where('citie_id',$request->get('gouv_id_search') )->pluck('prestataire_id')->toArray();

                $prestatairesville = DB::table('prestataires')->where('ville','=',$request->get('ville_id_search'))->pluck('id')->toArray();

                 $result=array_intersect($idprestatairegouv,$prestatairesville);
    

           $result=array_unique($result);
           //$prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id']);
           if($request->get('pres_id_search'))
               {
                $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                 $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
                }

            }

            // 0101

            if ($request->get('typepres_id_search') == null && $request->get('gouv_id_search')!= null && $request->get('ville_id_search')== null && $request->get('spec_id_search')!=null )
            {
      

                $idprestatairegouv= DB::table('cities_prestataires')->where('citie_id',$request->get('gouv_id_search') )->pluck('prestataire_id')->toArray();


                 $idprestatairespec= DB::table('specialites_prestataires')->where('specialite',$request->get('spec_id_search'))->pluck('prestataire_id')->toArray();


                 $result=array_intersect( $idprestatairegouv,$idprestatairespec);
    
       
           $result=array_unique($result);
           //$prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id']);

           if($request->get('pres_id_search'))
               {
                $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                 $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
                }

            }

            // 0111

             if ($request->get('typepres_id_search') == null && $request->get('gouv_id_search')!= null && $request->get('ville_id_search')!= null && $request->get('spec_id_search')!=null )
            {
           
                $idprestatairegouv= DB::table('cities_prestataires')->where('citie_id',$request->get('gouv_id_search') )->pluck('prestataire_id')->toArray();

                $prestatairesville = DB::table('prestataires')->where('ville','=',$request->get('ville_id_search'))->pluck('id')->toArray();

                 $idprestatairespec= DB::table('specialites_prestataires')->where('specialite',$request->get('spec_id_search'))->pluck('prestataire_id')->toArray();
                
                 $result2=array_intersect($prestatairesville, $idprestatairespec);
                 $result1=array_intersect($result2, $idprestatairegouv);
                 $result=array_intersect( $result1,$result2);
    

           $result=array_unique($result);
           //$prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id']);

           if($request->get('pres_id_search'))
               {
                $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                 $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
                }

            }

            ///   fin cas 2--------------------------------------------------------------------------------

            //    cas 3  

            //0010

             if ($request->get('typepres_id_search') == null && $request->get('gouv_id_search')== null && $request->get('ville_id_search')!= null && $request->get('spec_id_search')==null )
            {
             
                $prestatairesville = DB::table('prestataires')->where('ville','=',$request->get('ville_id_search'))->pluck('id')->toArray();       
    
           $result=array_unique($prestatairesville);
          // $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id']);

           if($request->get('pres_id_search'))
               {
                $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                 $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
                }

            }

            //  0011

           if ($request->get('typepres_id_search') == null && $request->get('gouv_id_search')== null && $request->get('ville_id_search')!= null && $request->get('spec_id_search')!=null )
            {
                          
                $prestatairesville = DB::table('prestataires')->where('ville','=',$request->get('ville_id_search'))->pluck('id')->toArray();

                 $idprestatairespec= DB::table('specialites_prestataires')->where('specialite',$request->get('spec_id_search'))->pluck('prestataire_id')->toArray();
                
                 $result=array_intersect($prestatairesville, $idprestatairespec);             
          
           $result=array_unique($result);
           //$prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id']);
           if($request->get('pres_id_search'))
               {
                $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                 $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
                }

            }

           // fin cas 3

            // cas 4 
            // 0001

             if ($request->get('typepres_id_search') == null && $request->get('gouv_id_search')== null && $request->get('ville_id_search')== null && $request->get('spec_id_search')!=null )
            {
             
                 $idprestatairespec= DB::table('specialites_prestataires')->where('specialite',$request->get('spec_id_search'))->pluck('prestataire_id')->toArray();                                             
           $result=array_unique($idprestatairespec);
          // $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id']);
           if($request->get('pres_id_search'))
               {
                $prests=Prestataire::whereIn('id',array_values($result))->where(function($q) use($request)                   {                             
                               $q->where('name','like', '%'.$request->get('pres_id_search').'%')
                              ->orWhere('prenom','like', '%'.$request->get('pres_id_search').'%');
                                })->get(['id','name','civilite','prenom','ville','ville_id','annule']);
               }
               else
               {
                 $prests=Prestataire::whereIn('id',array_values($result))->get(['id','name','civilite','prenom','ville','ville_id','annule']);
                }

            }


        }// fin else 2
       }// fin else 1
       // $prests=$prests->sortBy('name');
       $prests = $prests->sortBy(function($prests) {
       return sprintf('%-12s%s', $prests->name,  $prests->prenom);
       });

      return view('prestataires.index', compact('prests'));  

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

                     if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                     $data=Dossier::where('current_status',$request->get('current_status'))->orderBy('id','DESC')->get();
                     }
                     else
                     {

                      $data=Dossier::where('current_status','actif')->orWhere('current_status','inactif')->orderBy('id','DESC')->get();

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

                       if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                     $data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();
                     }
                     else
                     {
                      
                      $data=Dossier::where(function($q){                             
                           $q->where('current_status','actif')->orWhere('current_status','inactif'); 
                           })->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();

                     }

                    /* $data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();*/

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

                   /*$datasearch=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();*/

                   if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                     $datasearch=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();
                     }
                     else
                     {
                      
                     $datasearch=Dossier::where(function($q){                             
                           $q->where('current_status','actif')->orWhere('current_status','inactif'); 
                           })->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();

                     }

                  }
              }


               if($request->get('current_status') && $request->get('customer_id_search')  && $request->get('nom_benef_search') != null &&  $request->get('pres_id_search')== null)

              {


                   if( (strcmp($request->get('date_debut') , "Invalid date")!= 0
                       && strcmp($request->get('date_fin') , "Invalid date") != 0 ) &&

                           ($request->get('date_debut') && $request->get('date_fin'))  )

                   {
                     //dd('okbr');



                     /*$da=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();*/
                     if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                     $da=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();
                     }
                     else
                     {
                      
                     $da=Dossier::where(function($q){                             
                           $q->where('current_status','actif')->orWhere('current_status','inactif'); 
                           })->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();

                     }

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

                  /*$data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->get();*/
                   if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                     $data=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();
                     }
                     else
                     {
                      
                     $data=Dossier::where(function($q){                             
                           $q->where('current_status','actif')->orWhere('current_status','inactif'); 
                           })->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();

                     }


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



                    /*$da = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();*/
                      if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                     /*$da=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();*/
                     $da = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->orderBy('dossiers.id','DESC')
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();
                     }
                     else
                     {
                      
                     /*$da=Dossier::where(function($q){                             
                           $q->where('current_status','actif')->orWhere('current_status','inactif'); 
                           })->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();*/

                      $da = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where(function($q){                             
                           $q->where('dossiers.current_status','actif')->orWhere('dossiers.current_status','inactif'); 
                           })
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->orderBy('dossiers.id','DESC')
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();



                     }


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

                     /*$data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();*/
                      if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                     /*$da=Dossier::where('current_status',$request->get('current_status'))->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();*/
                     $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->orderBy('dossiers.id','DESC')
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();
                     }
                     else
                     {
                      
                     /*$da=Dossier::where(function($q){                             
                           $q->where('current_status','actif')->orWhere('current_status','inactif'); 
                           })->where('customer_id',$request->get('customer_id_search'))->orderBy('id','DESC')->get();*/

                      $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where(function($q){                             
                           $q->where('dossiers.current_status','actif')->orWhere('dossiers.current_status','inactif'); 
                           })
                      ->where('dossiers.customer_id',$request->get('customer_id_search'))
                      ->orderBy('dossiers.id','DESC')
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();



                     }


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


                      //$da=Dossier::where('current_status',$request->get('current_status'))->get();
                      if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                     $da=Dossier::where('current_status',$request->get('current_status'))->orderBy('id','DESC')->get();
                     }
                     else
                     {
                      
                     $da=Dossier::where(function($q){                             
                           $q->where('current_status','actif')->orWhere('current_status','inactif'); 
                           })->orderBy('id','DESC')->get();

                     }

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

                          //$data=Dossier::where('current_status',$request->get('current_status'))->get();
                      if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                     $data=Dossier::where('current_status',$request->get('current_status'))->orderBy('id','DESC')->get();
                     }
                     else
                     {
                      
                     $data=Dossier::where(function($q){                             
                           $q->where('current_status','actif')->orWhere('current_status','inactif'); 
                           })->orderBy('id','DESC')->get();

                     }

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


                      /* $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();*/

                      if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                    /* $da=Dossier::where('current_status',$request->get('current_status'))->orderBy('id','DESC')->get();*/

                     $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->orderBy('dossiers.id','DESC')
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();
                     }
                     else
                     {
                      
                     /*$da=Dossier::where(function($q){                             
                           $q->where('current_status','actif')->orWhere('current_status','inactif'); 
                           })->orderBy('id','DESC')->get();*/

                     $data = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where(function($q){                             
                           $q->where('dossiers.current_status','actif')->orWhere('dossiers.current_status','inactif'); 
                           })
                      ->orderBy('dossiers.id','DESC')
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();

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

                    /* $datasearch = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();*/

                             if($request->get('current_status')!= 'ecai') // ecai= en cours (actif + inactif)
                     {
                    /* $da=Dossier::where('current_status',$request->get('current_status'))->orderBy('id','DESC')->get();*/

                     $datasearch = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where('dossiers.current_status',$request->get('current_status'))
                      ->orderBy('dossiers.id','DESC')
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();
                     }
                     else
                     {
                      
                     /*$da=Dossier::where(function($q){                             
                           $q->where('current_status','actif')->orWhere('current_status','inactif'); 
                           })->orderBy('id','DESC')->get();*/

                     $datasearch = DB::table('prestations')
                      ->join('dossiers', 'dossiers.id', '=', 'prestations.dossier_id')
                      ->join('prestataires', 'prestataires.id', '=', 'prestations.prestataire_id')
                      ->where('prestataires.id','=', $request->get('pres_id_search'))
                      ->where(function($q){                             
                           $q->where('dossiers.current_status','actif')->orWhere('dossiers.current_status','inactif'); 
                           })
                      ->orderBy('dossiers.id','DESC')
                      ->select('dossiers.*', 'prestataires.name')
                      ->get();

                     }

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
->orderBy('dossiers.id','DESC')
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
->orderBy('dossiers.id','DESC')
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
->orderBy('dossiers.id','DESC')
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
->orderBy('dossiers.id','DESC')
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
->orderBy('dossiers.id','DESC')
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
->orderBy('dossiers.id','DESC')
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
->orderBy('dossiers.id','DESC')
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
->orderBy('dossiers.id','DESC')
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



 
