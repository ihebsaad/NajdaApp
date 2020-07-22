@extends('layouts.fulllayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

 <link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

@section('content')
    <?php use \App\Http\Controllers\PrestatairesController;
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
                            <label>Prestataire </label>
                            <select class="form-control select2" name="pres_id_search" id="pres_id_search">
                                <option value="">sélectionner</option>

                                @foreach(App\Prestataire::get() as $p)

                                 <option value="{{$p->id}}">{{$p->name}}</option>  

                                @endforeach
                            </select>
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
                            <div class="form-group">
                                <label>Ville </label>
                                <select id="ville_id_search" name="ville_id_search" class="form-control js-example-placeholder-single select2" >
                                    <option value="">sélectionner</option>
                                      @foreach(App\Ville::get() as $c)

                                         <option value="{{$c->id}}">{{$c->name}}</option>

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
                <th style="width:15%">Spécialités</th>
                <th style="width:10%">Actions</th>
             </tr>
            <tr style="font-size:14px;">
                <th style="width:20%">Prestataire</th>
                <th style="width:20%">Type de prestation</th>
                <th style="width:20%">Gouvernorats</th>
                <th style="width:10%">Ville</th>
                <th style="width:20%">Spécialités</th>
                <th style="width:10%"> </th>

            </tr>
            </thead>
            <tbody>
            @if(isset($prests))
            @foreach($prests as $prestataire)
                <?php $id= $prestataire->id ;$ville='';
                if($prestataire->ville !=''){$ville=$prestataire->ville;}else{


                     $villeid=intval($prestataire->ville_id );
            /*    if (isset($villes[$villeid]['name']) ){if($villeid>0) {$ville=$villes[$villeid-1]['name'];}}
                else{$ville=$prestataire['ville'];}*/
                }

                $gouvs=  PrestatairesController::PrestataireGouvs($id);
                $typesp=  PrestatairesController::PrestataireTypesP($id);
                $specs=  PrestatairesController::PrestataireSpecs($id);
                ?>

                <tr>
                    <td style="font-size:14px;width:30%"><a href="{{action('PrestatairesController@view', $id)}}" ><?php echo ' <b> '. $prestataire->civilite .' '. $prestataire->name .'</b> '.$prestataire->prenom; ?></a></td>
                    <td style="font-size:12px;width:20%"><?php     foreach($typesp as $tp){echo PrestatairesController::TypeprestationByid($tp->type_prestation_id).',  ';}?></td>
                    <td style="font-size:12px;width:15%"><?php foreach($gouvs as $gv){echo PrestatairesController::GouvByid($gv->citie_id).',  ';}?></td>
                    <td style="font-size:12px;width:10%"><?php echo $ville; ?></td>
                    <td style="font-size:12px;width:15%"><?php   foreach($specs as $sp){echo  PrestatairesController::SpecialiteByid($sp->specialite).',  ';}?></td>
                    <td style="font-size:13px;width:10%">
                        @can('isAdmin')
  	<?php 
 	$count1= \App\Facture::where('prestataire',$prestataire['id'])->count();
 	$count2= \App\Intervenant::where('prestataire_id',$prestataire['id'])->count();
 	$count3= \App\Prestation::where('prestataire_id',$prestataire['id'])->count();
 	$count4= \App\Evaluation::where('prestataire',$prestataire['id'])->count();
	$count= $count1+$count2+$count3+$count4;
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
   
      var start = moment("01/01/2019", "DD/MM/YYYY");
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
 $(document).ready(function() {
  
    $("#pres_id_search").select2();
    $("#typepres_id_search").select2();
    $("#ville_id_search").select2();
    $("#spec_id_search").select2();
    $("#gouv_id_search").select2();




 });

 </script>

@stop