<?php

namespace App\Http\Controllers;
use App\Adresse;
use App\ClientGroupe;
use App\Doc;
use App\TypePrestation;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Client ;
use App\Ville ;
use DB;
use Illuminate\Support\Facades\Cache;


class ClientsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $minutes= 120;
        $minutes2= 600;

        //      $typesMissions=TypeMission::get();
        $dossiers = Cache::remember('dossiers',$minutes,  function () {

            return Dossier::all();
        });

        $villes = Cache::remember('villes',$minutes2,  function () {

            return Ville::all();

        });

      /*  $clients = Cache::remember('clients',$minutes,  function () {

            return  Client::orderBy('created_at', 'desc')->paginate(10000000);

        });*/
       $clients = Client::orderBy('created_at', 'desc')->paginate(10000000);

        return view('clients.index',['dossiers' => $dossiers,'villes' => $villes], compact('clients'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('clients.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = new Client([
             'nom' =>trim( $request->get('nom')),
             'typepres' => trim($request->get('typepres')),
            // 'par'=> $request->get('par'),

        ]);

        $client->save();
        return redirect('/clients')->with('success', ' ajouté avec succès');

    }

    public function saving(Request $request)
    {
        if( ($request->get('name'))!=null) {

            $client = new Client([
                'name' => $request->get('name'),
                'pays' => $request->get('pays'),

            ]);
            if ($client->save())
            { $id=$client->id;

                return url('/clients/view/'.$id)/*->with('success', 'Dossier Créé avec succès')*/;
                // return  redirect()->route('dossiers.view', ['id' =>$iddoss]);
                //  return  $iddoss;
            }

            else {
                return url('/clients');
            }
        }

       // return redirect('/clients')->with('success', 'ajouté avec succès');

    }

    public function addressadd(Request $request)
    {
        if( ($request->get('champ'))!=null) {

            $parent=$request->get('parent');
            $adresse = new Adresse([
              //  'nom' => $request->get('nom'),
              //  'prenom' => $request->get('prenom'),
                'champ' => $request->get('champ'),
                'type' => $request->get('type'),
                'nature' => $request->get('nature'),
                'remarque' => $request->get('remarque'),
                'parent' => $parent,

            ]);

            if ($adresse->save())
            { $id=$adresse->id;

                return url('/clients/view/'.$parent)/*->with('success', 'Dossier Créé avec succès')*/;
                // return  redirect()->route('dossiers.view', ['id' =>$iddoss]);
                //  return  $iddoss;
            }

            else {
                return url('/clients');
            }
        }

        // return redirect('/clients')->with('success', 'ajouté avec succès');

    }

    public function addressadd2(Request $request)
    {
        if( ($request->get('champ'))!=null) {

            $parent=$request->get('parent');
            $adresse = new Adresse([
                'champ' => $request->get('champ'),
                'nom' => $request->get('nom'),
                'nature' => $request->get('nature'),
                'parent' => $parent,
            ]);

            if ($adresse->save())
            { $id=$adresse->id;

                return url('/clients/view/'.$parent)/*->with('success', 'Dossier Créé avec succès')*/;
                // return  redirect()->route('dossiers.view', ['id' =>$iddoss]);
                //  return  $iddoss;
            }

            else {
                return url('/clients');
            }
        }

        // return redirect('/clients')->with('success', 'ajouté avec succès');

    }

    public function addressadd3(Request $request)
    {
        if( ($request->get('nom'))!=null) {

            $parent=$request->get('parent');
            $adresse = new Adresse([
                'nom' => $request->get('nom'),
                'prenom' => $request->get('prenom'),
                'fonction' => $request->get('fonction'),
                'tel' => $request->get('tel'),
                'mail' => $request->get('email'),
                'fax' => $request->get('fax'),
                'remarque' => $request->get('observ'),
                'nature' => $request->get('nature'),
                'parent' => $parent,
            ]);

            if ($adresse->save())
            { $id=$adresse->id;

                return url('/clients/view/'.$parent)/*->with('success', 'Dossier Créé avec succès')*/;
                // return  redirect()->route('dossiers.view', ['id' =>$iddoss]);
                //  return  $iddoss;
            }

            else {
                return url('/clients');
            }
        }

        // return redirect('/clients')->with('success', 'ajouté avec succès');

    }
    public function updating(Request $request)
    {

        $id= $request->get('client');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Client::where('id', $id)->update(array($champ => $val));



    }

    public function updatingnature(Request $request)
    {

        $id= $request->get('client');
        $champ= strval($request->get('champ'));
        $val= $request->get('val');


        $nature=$this->NatureById($id);
        if ($nature !=''){
            Client::where('id', $id)->update(array($champ => $nature.$val));
        }
        else{
            Client::where('id', $id)->update(array($champ => $val));
        }


    }


    public function removenature(Request $request)
    {

        $id= $request->get('client');
        $champ= strval($request->get('champ'));
        $val= $request->get('val');

        $nature=$this->NatureById($id);
        $newnature=str_replace($val,"",$nature);
        $newnature=str_replace(',,',",",$newnature);
       // Client::where('id', $id)->update(array('nature' => $newnature));

        Client::where('id', $id)->update(array('nature'=> trim($newnature,',')));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $dossiers = Dossier::all();
        $docs = DB::table('docs')->select('id', 'nom')->get();
        $relations2 = DB::table('clients_docs')->select('client', 'doc')
            ->where('client',$id)->get();

        $groupes = DB::table('client_groupes')->select('id', 'label')->get();

        $countries = DB::table('apps_countries')->select('id', 'country_name')->get();

        $emails =   Adresse::where('nature', 'email')
            ->where('parent',$id)
            ->get();

        $tels =   Adresse::where('nature', 'tel')
            ->where('parent',$id)
            ->get();

        $faxs =   Adresse::where('nature', 'fax')
            ->where('parent',$id)
            ->get();

        $entites =   Adresse::where('nature', 'facturation')
            ->where('parent',$id)
            ->get();

        $qualites =   Adresse::where('nature', 'qualite')
            ->where('parent',$id)
            ->get();

        $reseaux =   Adresse::where('nature', 'reseau')
            ->where('parent',$id)
            ->get();

        $gestions =   Adresse::where('nature', 'gestion')
            ->where('parent',$id)
            ->get();


        $client = Client::find($id);
        return view('clients.view',['docs'=>$docs,'relations2'=>$relations2 ,'dossiers' => $dossiers,'groupes'=>$groupes,'countries'=>$countries,'emails'=>$emails,'tels'=>$tels,'faxs'=>$faxs,'entites'=>$entites,'qualites'=>$qualites ,'reseaux'=>$reseaux,'gestions'=>$gestions], compact('client'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $client = Client::find($id);
        $dossiers = Dossier::all();

        return view('clients.edit',['dossiers' => $dossiers], compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $client = Clients::find($id);

       // if( ($request->get('ref'))!=null) { $client->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $client->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $client->user_type = $request->get('affecte');}

        $client->save();

        return redirect('/clients')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        $client->delete();

        return redirect('/clients')->with('success', '  Supprimé  ');
    }

    public static function GroupeById($id)
    {
         $groupe = ClientGroupe::find($id);

        return $groupe['label'];

    }

    public  function removetypeprest(Request $request)
    {
        $client= $request->get('client');
        $typeprest= $request->get('typeprest');


        DB::table('clients_type_prestations')
            ->where([
                ['client_id', '=', $client],
                ['type_prestation_id', '=', $typeprest],
            ])->delete();



    }

    public  function createtypeprest(Request $request)
    {
        $client= $request->get('client');
        $typeprest= $request->get('typeprest');


        DB::table('clients_type_prestations')->insert(
            ['client_id' => $client,
                'type_prestation_id' => $typeprest]
        );



    }


    public static function NatureById($id)
    {
        $client = Client::find($id);
        if (isset($client['nature'])) {
            return $client['nature'].',';
        }else{return '';}

    }


    public static function ClientChampById($champ,$id)
    {
        $client = Client::find($id);
        if (isset($client[$champ])) {
            return $client[$champ] ;
        }else{return '';}

    }


    public static function ClientChamp2ById($champ,$id)
    {
        $client = Client::find($id);
        if (isset($client[$champ])) {
            return $client[$champ].',';
        }else{return '';}

    }


    public static function deleteaddress( $id)
    {
        $adresse = Adresse::find($id);
        $adresse->delete();
        return back();

    }


// Modifier les champs des N° tels, fax, Email ...
    public function updateaddress(Request $request)
    {

        $id= $request->get('id');
        $champ= strval($request->get('champ'));
        $val=strval($request->get('val'));
         //  $dossier = Dossier::find($id);
        // $dossier->$champ =   $val;
        Adresse::where('id', $id)->update(array($champ => $val));



    }


}

