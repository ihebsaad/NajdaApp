@extends('layouts.adminlayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.jqueryui.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.jqueryui.min.css">

<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

@section('content')

    <?php use App\Dossier;use App\Http\Controllers\DossiersController;
    use \App\Http\Controllers\UsersController;
    use \App\Http\Controllers\ClientsController;
    use \App\Http\Controllers\PrestationsController;
    $users=UsersController::ListeUsers();

    $CurrentUser = auth()->user();

    $iduser=$CurrentUser->id;

    ?>
    <style>
        .uper {
            margin-top: 10px;
        }
        .no-sort input{display:none;}
    </style>
    <div class="uper">

        <div class="portlet box grey">
            <div class="row">
                <div class="col-lg-6"><h2>Factures</h2></div>
                <div class="col-lg-6">
                    <button id="addgr" class="btn btn-md btn-success"   data-toggle="modal" data-target="#create"><b><i class="fas fa-plus"></i> Ajouter une facture </b></button>
                </div>
            </div>
        </div>

        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th style="width:5%">ID</th>
                <th style="width:15%">Dossier</th>
                <th style="width:15%">Assistance</th>
                <th style="width:15%">Intervanant</th>
                 <th style="width:10%">N°Facture</th>
                <th style="width:10%">Arrivé</th>
                <th style="width:10%">Délai Email</th>
                <th style="width:10%">Délai Poste</th>

				<th class="no-sort" style="width:4%">Actions</th>
              </tr>
            <tr>
                <th style="width:5%">ID</th>
                <th style="width:15%">Dossier</th>
                <th style="width:15%">Assistance</th>
                <th style="width:15%">Intervanant</th>
                 <th style="width:10%">N°Facture</th>
                <th style="width:10%">Arrivé</th>
                <th style="width:10%">Délai Email</th>
                <th style="width:10%">Délai Poste</th>

                <th class="no-sort" style="width:4%">Actions</th>
            </tr>
            </thead>
            <tbody>
			<?php 
			
    $today=date('d-m-Y');
    $today=new DateTime($today);;
	?>
            @foreach($factures as $facture)
   <?php
   
   
$createdat=  date('d/m/Y H:i', strtotime($facture->created_at ));

    $date_valid=$facture['date_valid'];
    $date_arrive=$facture['date_arrive'];
    $dateposte=$facture['date_poste'];

    if($date_valid!=''){$dateemail=$date_valid;}else{$dateemail=$date_arrive;}

    $dateEmail=str_replace('/','-',$dateemail) ;
    $datePoste=str_replace('/','-',$dateposte) ;
    $dateEmail= new DateTime($dateEmail);
    $datePoste= new DateTime($datePoste);
   // $dateEmail=date_create($dateEmail);
   // $datePoste=date_create($dateEmail);


    $diffEmail=date_diff($dateEmail,$today);
   // $diffEmail->format("%R%a ");
    $diffPoste=date_diff($datePoste,$today);
   
   
   
   ?>
                <tr>

				<td style="width:5%"  ><a href="{{action('FacturesController@view', $facture->id)}}" ><?php echo sprintf("%05d",$facture->id);?></a></td>
                    <td style="width:15%">
                        <?php if(isset($facture->iddossier)){ $iddossier= $facture->iddossier; $Folder= App\Dossier::where('id',$iddossier)->first();$ref=$Folder['reference_medic'] ; $abn= $Folder['subscriber_name'] .' '.$Folder['subscriber_lastname'] ; 
                         ?>
                           <a href="{{action('DossiersController@view', $facture->iddossier)}}" >
                               <?php   echo     $ref.' ' .$abn ; ?></a> 
					<?php } ?>							   
                    </td>
                      <td style="width:15%">
                        <?php
                            $client =   $Folder['customer_id'] ;
                            echo   ClientsController::ClientChampById('name',$client);?>
                    </td>
                      <td style="width:15%">
                        <?php $prest=  $facture->prestataire; ?>
                        <a  href="{{action('PrestatairesController@view', $prest)}}" ><?php echo PrestationsController::PrestataireById($prest);  ?>
                        </a>
                    </td>
                    <td style="width:10%" >{{$facture->reference}}</td>
                    <td  style="width:10%">{{$facture->date_arrive}}</td>
                    <td style="width:10%"  ><?php echo   $diffEmail->format("%R%a "); ?> jours</td>
                    <td style="width:10%"  ><?php echo   $diffPoste->format("%R%a "); ?> jours</td>
 					<td style="width:4%"   >
                        @can('isAdmin')
                            <a  href="{{action('FacturesController@destroy', $facture['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                <span class="fa fa-fw fa-trash-alt"></span>
                            </a>
                        @endcan
                    </td>
 
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>






    <!-- Modal -->
    <div class="modal fade" id="create"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter une facture</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <form method="post" >
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="type">Date d'arrivée :</label>
                                <input   id="date_arrive"   value='<?php echo date('d/m/Y'); ?>' class="form-control datepicker-default "  />

                            </div>

							<div class="form-group">
                                <label for="type">N° de Facture :</label>
                                <input class="form-control"  id="reference"  type="text" class="form-control input"   />

                            </div>

                            <div class="form-group">
                                <label for="type"> Dossier : </label>
                                     <?php         $dossiers = Dossier::orderBy('created_at', 'desc')->get(); ?>
                                    <select id ="iddossier"  class="form-control " style="width: 100%;color:black!important;">
                                        <option></option>
                                        <?php foreach($dossiers as $ds)

                                        {
                                            echo '<option style="color:black!important" title="'.$ds->id.'" value="'.$ds->id.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name .' '.$ds->subscriber_lastname .' </option>';}     ?>
                                    </select>
                             </div>
                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" id="add" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>



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

    <!--select css-->
    <link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

    <script src="{{ asset('public/js/select2/js/select2.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#iddossier").select2();


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
                var date_arrive = $('#date_arrive').val();
                var reference = $('#reference').val();
                var dossier = $('#iddossier').val();
                if ((date_arrive != '' ) || (reference != '' )   )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('factures.saving') }}",
                        method:"POST",
                        data:{reference:reference,date_arrive:date_arrive,dossier:dossier, _token:_token},
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
@stop