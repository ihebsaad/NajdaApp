@extends('layouts.mainlayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

@section('content')
    <?php use \App\Http\Controllers\PrestatairesController;

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
                <div class="col-lg-6">Prestataires</div>
                <div class="col-lg-6">
                    <button id="addprest" class="btn btn-md btn-success"   data-toggle="modal" data-target="#create"><b><i class="fas fa-plus"></i> Créer un Prestataire</b></button>
                </div>
            </div>
        </div>

        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th style="width:35%">Nom</th>
                <th style="width:25%">Spécialité</th>
                <th style="width:10%">Priorité</th>
                <th style="width:20%">Ville</th>
             </tr>
            <tr>
                <th style="width:35%">Nom</th>
                <th style="width:25%">Spécialité</th>
                <th   style="width:10%">Priorité</th>
                <th style="width:20%">Ville</th>
            </tr>
            </thead>
            <tbody>
            @foreach($prestataires as $prestataire)
                <?php $ordre=$prestataire['ordre'];
                if ($ordre==1){$class="bg-primary";}
                else {
                    if ($ordre==2){$class="bg-info";}
                    else {
                        if ($ordre==3){$class="bg-danger";}
                        if ($ordre==0){$class="bg-warning";}

                    }

                    }
                     $villeid=intval($prestataire['ville_id']);
                if (isset($villes[$villeid]['name']) ){$ville=$villes[$villeid]['name'];}
                else{$ville=$prestataire['ville'];}


                ?>

                <tr>
                    <td style="width:35%"><a href="{{action('PrestatairesController@view', $prestataire['id'])}}" >{{$prestataire->name}}</a></td>
                    <td style="width:25%">{{$prestataire->specialite}}</td>
                    <td style="width:10%;text-align:center"   class="<?php echo $class;?> ">{{$prestataire->ordre}}</td>
                    <td style="width:20%"><?php     /*$prestataire['ville_id'] ; */
                     echo $ville ;
                        ?></td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>






    <?php use \App\Http\Controllers\UsersController;
    $users=UsersController::ListeUsers();

    $CurrentUser = auth()->user();

    $iduser=$CurrentUser->id;

    ?>
    <!-- Modal -->
    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Créer un nouveau Prestataire</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <form method="post" >
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="type">Nom :</label>
                            <input class="form-control" type="text" id="nom" />

                            </div>

                            <div class="form-group">
                                <label for="type">Spécialité :</label>
                                <input class="form-control" type="text" id="specialite" />
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


    <script type="text/javascript">
        $(document).ready(function() {


            $('#mytable thead tr:eq(1) th').each( function () {
                var title = $('#mytable thead tr:eq(0) th').eq( $(this).index() ).text();
                $(this).html( '<input class="searchfield" type="text"   />' );
            } );

            var table = $('#mytable').DataTable({
                orderCellsTop: true,
                dom: 'Bflrtip',
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
            table.columns().every(function (index) {
                $('#mytable thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function () {
                    table.column($(this).parent().index() + ':visible')
                        .search(this.value)
                        .draw();
                });
            });



            $('#add').click(function(){
                var nom = $('#nom').val();
                 var specialite = $('#specialite').val();
                if ((nom != '')&&(specialite != '') )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('prestataires.saving') }}",
                        method:"POST",
                        data:{nom:nom,specialite:specialite, _token:_token},
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