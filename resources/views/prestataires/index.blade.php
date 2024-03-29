@extends('layouts.fulllayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.jqueryui.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.jqueryui.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>

<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

 <link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

@section('content')
    <?php use \App\Http\Controllers\PrestatairesController;
use \App\Prestataire;
use \App\Adresse;
$user = auth()->user();
 $user_type=$user->user_type;
    //echo json_encode($villes);
    //  collect($villes);

   /// $filtered = $villes->where('id', 10)->pluck('name');
   // echo $filtered ;

    ?>
    <style>
        .uper {
            margin-top: 10px;
        }
    </style>
    <div class="uper">
        <div class="portlet box grey">
            <div class="row">
                <div class="col-lg-6"><h4>Liste des intervenants</h4></div>
                <div class="col-lg-6">
               <?php if($user_type=='admin' || $user_type=='superviseur' || $user_type=='autonome' ){ ?>     <a    href="{{route('prestataires.create',['id'=>0])}}" class="btn btn-md btn-success"   ><b><i class="fas fa-plus"></i> Ajouter un Intervenant</b></a>&nbsp; &nbsp;<?php } ?>
                    <a class="btn btn-default" id="recherchertp" href="{{url('/prestataire/tousprestataires')}}"> Afficher tous les intervenants</a>
                </div>
            </div>
        </div>

        <!-- debut recherche avancee sur dossiers-->


      <div class="portlet box blue">
               <div  style="background-color:#4fc1e9; height: 45px; margin-bottom: 0px; padding: 2px;">
                
                   <h4 style="cursor:pointer"  id="search">  &nbsp;<strong> <i class="fa fa-search"></i> &nbsp;Recherche avancée </strong></h4>
                    
                </div>
            <div class="portlet-title" style="margin-top: 0px; padding-top: 0px;">
                
            </div>
            <div class="portlet-body"  id="searchbox"    >
                <form accept-charset="utf-8" id="searchDossierform" action="{{route('recherchePrestataire.avancee')}}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                            <label>Prestataire </label>
                             </div>
                             <div class="row">
                            <input type="text" list="pres_search" id="presid"  name="pres_id_search" value="" class="form-control select2"/>
                                <datalist id="pres_search" >
                                @foreach(App\Prestataire::orderBy('name','ASC')->get(["id","name","prenom"]) as $p)

                                 <option value="{{$p->name}} {{$p->prenom}}" data-value="{{$p->id}}" ></option>  

                                @endforeach
                                </datalist>
                                <input type="hidden" value="" id="pres_id_search_hidden" name="pres_id_search_hidden" />
                                 </div>


                            {{--<select class="form-control select2" name="pres_id_search" id="pres_id_search">
                                <option value="">sélectionner</option>

                                @foreach(App\Prestataire::orderBy('name','ASC')->get(["id","name","prenom"]) as $p)

                                 <option value="{{$p->id}}">{{$p->name}} {{$p->prenom}}</option>  

                                @endforeach
                            </select>--}}
                        </div>

                        <div class="col-md-4">
                            <label>type de Prestation </label>
                    <select class="form-control select2" name="typepres_id_search" id="typepres_id_search">
                                <option value="">sélectionner</option>

                                @foreach(App\TypePrestation::get(["id","name"]) as $pp)

                                 <option value="{{$pp->id}}">{{$pp->name}}</option>  

                                @endforeach
                            </select>
                        </div>

                         <div class="col-md-4">
                            <div class="form-group">
                                <label>Gouvernorat </label>
                                <select id="gouv_id_search" name="gouv_id_search" class="form-control js-example-placeholder-single select2" >
                                    <option value="">sélectionner</option>
                                      @foreach(App\Citie::get() as $c)

                                         <option value="{{$c->id}}">{{$c->name}}</option>

                                      @endforeach
                                                                    
                                </select>
                            </div>
                        </div>

                           </div>
                             <div class="row">

                          


                        <div class="col-md-4">
 <label>Ville </label>
                            <div class="form-group"> 
                               
                                <select autocomplete class="select2 form-control  col-lg-12 " style="width:400px" name="ville_id_search"    id="ville_id_search">
                                         <option value="">Selectionner</option>
                                         <option value="toutes">toutes</option>
                                         @foreach($villes as $pres)

                                             <option   value="<?php echo $pres->ville;?>"> <?php echo $pres->ville;?></option>
                                         @endforeach

                                     </select>
                                
                                 
                             
                            </div>

                        
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Spécialité </label>
                                <select id="spec_id_search" name="spec_id_search" class="form-control js-example-placeholder-single select2" >
                                    <option value="">sélectionner</option>
                                      @foreach(App\Specialite::get() as $c)

                                         <option value="{{$c->id}}">{{$c->nom}}</option>

                                      @endforeach
                                                                    
                                </select>
                            </div>
                        </div>


                        <!--<div class="col-md-4">
                            
                            <div class="form-group ">
                                <label >Date de création</label>
                                <input type="text" name="daterange" class="form-control" value="" />
                                <input type="hidden" name="date_debut" id="date_debut_recherche" value="" />
                                <input type="hidden" name="date_fin" id="date_fin_recherche" value="" />
                            </div>
                        </div>-->
                        </div>

                        
                    <div class="row">

                     
                        <div class="col-md-12">
                            
                            <button class="btn btn-circle blue pull-right" id="rechercher" type="submit" style="margin-top: 20px;margin-right:20px;">  Rechercher</button>
                        </div>
                    </div>
                </form>
            </div>

          
        </div>


 <!-- table-->
  <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th style="width:30%">Prestataire</th>
               <th style="width:20%;font-size:14px;">Type de prestations</th>
                <th style="width:15%">Gouvernorats</th>
                <th style="width:10%">Ville</th>
 <?php if($user_type=='admin')
{ 
?>
<th style="width:15%">Adresse</th>
<?php
}
?>
                <th style="width:15%">Spécialités</th>
 <?php if($user_type=='admin')
{ 
?>
<th style="width:15%">Téléphones</th>
<th style="width:15%">fax</th>
<th style="width:15%">Adresses mail</th>

 <?php } 
?>
<th style="width:15%">Statut</th>

                <th style="width:10%">Actions</th>
             </tr>
            <tr style="font-size:14px;">
                <th style="width:20%">Prestataire</th>
                <th style="width:20%">Type de prestation</th>
                <th style="width:20%">Gouvernorats</th>
                <th style="width:10%">Ville</th>
 <?php if($user_type=='admin')
{ 
?>
<th style="width:15%">Adresse</th>
<?php
}
?>
                <th style="width:20%">Spécialités</th>
<th style="width:15%">Statut</th>
  <?php if($user_type=='admin')
{ 
?>


<th style="width:15%">Téléphones</th>
<th style="width:15%">fax</th>
<th style="width:15%">Adresses mail</th>

 <?php }
?>
                <th style="width:10%"> </th>

            </tr>
            </thead>
            <tbody>
            @if(isset($prests))
            @foreach($prests as $prestataire)
                <?php $id= $prestataire->id ;
                /*  $ville='';
                if($prestataire->ville !=''){$ville=$prestataire->ville;}else{


                     $villeid=intval($prestataire->ville_id );
            /*    if (isset($villes[$villeid]['name']) ){if($villeid>0) {$ville=$villes[$villeid-1]['name'];}}
                else{$ville=$prestataire['ville'];}
                }*/

                $gouvs=  PrestatairesController::PrestataireGouvs($id);
                $typesp=  PrestatairesController::PrestataireTypesP($id);
                $specs=  PrestatairesController::PrestataireSpecs($id);
 $tels=  Adresse::where('nature','telinterv')->where('parent',$id)->get();
                $faxs=  Adresse::where('nature','faxinterv')->where('parent',$id)->get();
$emails=  Adresse::where('nature','emailinterv')->where('parent',$id)->get();
                $specs=  PrestatairesController::PrestataireSpecs($id);
$Prestataire=  Prestataire::where('id',$id)->first();
                ?>

                <tr>
                    <td style="font-size:14px;width:30%"><a href="{{action('PrestatairesController@view', $id)}}" ><?php echo ' <b> '. $prestataire->civilite .' '. $prestataire->name .'</b> '.$prestataire->prenom; ?></a></td>
                    <td style="font-size:12px;width:20%"><?php     foreach($typesp as $tp){echo PrestatairesController::TypeprestationByid($tp->type_prestation_id).',  ';}?></td>
                    <td style="font-size:12px;width:15%"><?php foreach($gouvs as $gv){echo PrestatairesController::GouvByid($gv->citie_id).',  ';}?></td>
                    <td style="font-size:12px;width:10%"><?php echo $prestataire->ville; ?></td>
  @can('isAdmin')
<td style="font-size:12px;width:15%"  ><?php echo $Prestataire->adresse   ?></td>
@endcan
                    <td style="font-size:12px;width:15%"><?php   foreach($specs as $sp){echo  PrestatairesController::SpecialiteByid($sp->specialite).',  ';}?></td>
  @can('isAdmin')
<td style="font-size:12px;width:15%"  ><?php  foreach($tels as $tel){echo $tel->champ.',  ' ;}?></td>
<td style="font-size:12px;width:15%" ><?php  foreach($faxs as $fax){echo $fax->champ.',  ' ;}?></td>
<td style="font-size:12px;width:15%"  ><?php  foreach($emails as $email){echo $email->champ.',  ' ;}?></td>
@endcan
 <td  ><?php if ($prestataire->annule ==0){echo 'Actif';}else{echo 'Désactivé';} ?></td>

 

                    <td style="font-size:13px;width:10%">
                        @can('isAdmin')

    <?php 
    $count1= \App\Facture::where('prestataire',$prestataire['id'])->count();
    //$count2= \App\Intervenant::where('prestataire_id',$prestataire['id'])->count();
    $count3= \App\Prestation::where('prestataire_id',$prestataire['id'])->where('effectue',1)->count();
    //$count4= \App\Evaluation::where('prestataire',$prestataire['id'])->count();
    $count= $count1+$count3;
    if ($count>0){
        echo 'Suppression interdite'; 
    }else{ ?>                         

                           <a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('PrestatairesController@destroy', $prestataire['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                <span class="fa fa-fw fa-trash-alt"></span> Supp
                            </a>
    <?php  } ?>             
                            
                        @endcan</td>

                </tr>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>



    <?php
    use \App\Http\Controllers\UsersController;
     $users=UsersController::ListeUsers();

    $CurrentUser = auth()->user();

    $iduser=$CurrentUser->id;


    ?>
 <input type="hidden" value="<?php echo $user_type ?>" id="usertype" name="usertype" />



@endsection



@section('footer_scripts')

    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/dataTables.rowReorder.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/dataTables.scroller.js') }}" ></script>

    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/dataTables.buttons.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/dataTables.responsive.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/buttons.colVis.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/buttons.html5.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/buttons.print.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/buttons.bootstrap.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/buttons.print.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/pdfmake.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/vfs_fonts.js') }}" ></script>

    <style>.searchfield{width:100px;}</style>


    <script type="text/javascript">
        $(document).ready(function() {

var usertype=document.getElementById('usertype').value;  
//alert(usertype);
if(usertype=="admin"){
            $('#mytable thead tr:eq(1) th').each( function () {
     

var title = $('#mytable thead tr:eq(0) th').eq( $(this).index() ).text();
                $(this).html( '<input class="searchfield" type="text"   />' );
            } );

            var table = $('#mytable').DataTable({
                orderCellsTop: true,
                          order:[],
 /*dom : '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
                responsive:true,
                buttons: [

                    'csv', 'excel', 'pdf', 'print'
                ],*/
 dom: 'lBfrtip',
     
                responsive:true,

				
			


buttons: [						 
                  
				 {
                    extend: 'excel',
                    text: '  Excel',
  title: 'liste de prestataires',
					className : 'fa fa-file-excel-o',
                    exportOptions: {
                    columns: [ 0,1,2,3,4,5,6,7,8]
               	}
                    },				
				
					/*,
				 {
                    extend: 'copy',
                    text: '  Copier',
					className : 'fa fa-copy',					 
                    exportOptions: {
                    columns: [ 0,1,2,3,4,5 ]
                	}
                  },
		   		{
                    extend: 'colvis',
                    text: '  Colonnes',
					className : 'fa fa-hand-o-up',	
				}
*/
                ], 

                "columnDefs": [ {
                    "targets": 'no-sort',
                    "orderable": false,
                } ]
                ,
                "language":
                    {
                        "decimal":        "",
                        "emptyTable":     "Pas de données",
                        "info":           "affichage de  _START_ à _END_ de _TOTAL_ entrées",
                        "infoEmpty":      "affichage 0 à 0 de 0 entrées",
                        "infoFiltered":   "(Filtrer de _MAX_ total d`entrées)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "affichage de _MENU_ entrées",
                        "loadingRecords": "chargement...",
                        "processing":     "chargement ...",
                        "search":         "Recherche:",
                        "zeroRecords":    "Pas de résultats",
                        "paginate": {
                            "first":      "Premier",
                            "last":       "Dernier",
                            "next":       "Suivant",
                            "previous":   "Précédent"
                        },
                        "aria": {
                            "sortAscending":  ": activer pour un tri ascendant",
                            "sortDescending": ": activer pour un tri descendant"
                        }
                    }

            });
}
else
{
$('#mytable thead tr:eq(1) th').each( function () {
     

var title = $('#mytable thead tr:eq(0) th').eq( $(this).index() ).text();
                $(this).html( '<input class="searchfield" type="text"   />' );
            } );

            var table = $('#mytable').DataTable({
                orderCellsTop: true,
                          order:[],
 dom : '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
                responsive:true,
                buttons: [

                    'csv', 'excel', 'pdf', 'print'
                ],

     
                responsive:true,
				
			



                "columnDefs": [ {
                    "targets": 'no-sort',
                    "orderable": false,
                } ]
                ,
                "language":
                    {
                        "decimal":        "",
                        "emptyTable":     "Pas de données",
                        "info":           "affichage de  _START_ à _END_ de _TOTAL_ entrées",
                        "infoEmpty":      "affichage 0 à 0 de 0 entrées",
                        "infoFiltered":   "(Filtrer de _MAX_ total d`entrées)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "affichage de _MENU_ entrées",
                        "loadingRecords": "chargement...",
                        "processing":     "chargement ...",
                        "search":         "Recherche:",
                        "zeroRecords":    "Pas de résultats",
                        "paginate": {
                            "first":      "Premier",
                            "last":       "Dernier",
                            "next":       "Suivant",
                            "previous":   "Précédent"
                        },
                        "aria": {
                            "sortAscending":  ": activer pour un tri ascendant",
                            "sortDescending": ": activer pour un tri descendant"
                        }
                    }

            });
}
// Apply the search
            function delay(callback, ms) {
                var timer = 0;
                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        callback.apply(context, args);
                    }, ms || 0);
                };
            }
            table.columns().every(function (index) {
                $('#mytable thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function () {
                    table.column($(this).parent().index() + ':visible')
                        .search(this.value)
                        .draw();
                });
                
                $('#mytable thead tr:eq(1) th:eq(' + index + ') input').keyup(delay(function (e) {
                    console.log('Time elapsed!', this.value);
                    $(this).blur();

                }, 2000));
            });



            $('#add').click(function(){
                var nom = $('#nom').val();
                 var prenom = $('#prenom').val();
                if ((nom != '')&&(prenom != '') )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('prestataires.saving') }}",
                        method:"POST",
                        data:{nom:nom,prenom:prenom, _token:_token},
                        success:function(data){

                            //   alert('Added successfully');
                            window.location =data;


                        }
                    });
                }else{
                    // alert('ERROR');
                }
            });




        });

    </script>

     <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  
      <script type="text/javascript" src="{{ URL::asset('public/js/moment/moment-timezone-with-data-1970-2030.min.js') }}"></script>
     
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

 <script>
    $(function() {

   // var start = moment().subtract(29, 'days');
   // var start = moment().set({'year': 2015, 'month': 0 , 'day': 0});
   
      var start = moment("01/01/2020", "DD/MM/YYYY");
    //var start ='01/01/2015' ;
    var end = moment();

    //var start ='';
    //var end = '';

    function cb(start, end) {
       /* $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));*/
       //$('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
      $('input[name="daterange"]').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));

       $('#date_debut_recherche').val(start.tz('Africa/Tunis').format('Y-MM-DD HH:mm:ss'));
       $('#date_fin_recherche').val(end.tz('Africa/Tunis').format('Y-MM-DD HH:mm:ss'));


    }

   // $('#reportrange').daterangepicker({
    $('input[name="daterange"]').daterangepicker({
        linkedCalendars: false,
        "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Valider",
        "cancelLabel": "Annuler",
        "fromLabel": "De",
        "toLabel": "à",
        "customRangeLabel": "Personnaliser",
        "daysOfWeek": [
            "Dim",
            "Lun",
            "Mar",
            "Mer",
            "Jeu",
            "Ven",
            "Sam"
        ],
        "monthNames": [
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Août",
            "Septembre",
            "Octobre",
            "Novembre",
            "Décembre"
        ],
        "firstDay": 1
         },
        startDate: start,
        endDate: end,
        ranges: {
           'Pas de date':["",""],
           'Aujourd hui': [moment(), moment()],
           'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Dernières 7 jours': [moment().subtract(6, 'days'), moment()],
           'Dernieres 30 jours': [moment().subtract(29, 'days'), moment()],
           'Ce mois': [moment().startOf('month'), moment().endOf('month')],
           'Dernier mois': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

});


</script>

<script>
    $('#presid').on('input', function() {
        const value = $(this).val();
        //alert(value);
        const data_value = $('#pres_search [value="' + value + '"]').data('value');
        document.getElementById("pres_id_search_hidden").value = data_value;
      });


 $(document).ready(function() {
  
    $("#pres_id_search").select2();
    $("#typepres_id_search").select2();
    $("#ville_id_search").select2();
    $("#spec_id_search").select2();
    $("#gouv_id_search").select2();




    /// fill phone number on select prest Prise en charge. (khaled)

   /* document.querySelector('input[list="pres_search"]').addEventListener('input', onInput);

    function onInput(e) {

       var input = e.target,
           val = input.value;
           list = input.getAttribute('list'),
           options = document.getElementById(list).childNodes;
           //alert(options);
      for(var i = 0; i < options.length; i++) {
          //alert(options[i].innerText);
          if(options[i].innerText)
          {
            //alert(val);
            //alert(options[i].innerText);
        if(String(options[i].innerText).trim() === String(val).trim()) {
            //alert(val);
          // An item was selected from the list
          document.getElementById("pres_id_search_hidden").value = options[i].getAttribute("ido");
          break;
        }
       }
      }
    }*/




 });

 

 </script>

@stop
