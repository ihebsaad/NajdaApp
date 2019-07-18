@extends('layouts.adminlayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

@section('content')
    <?php use \App\Http\Controllers\ClientsController;     ?>
    <style>
        .uper {
            margin-top: 10px;
        }
        table td {font-size:13px;}
    </style>
    <div class="uper">
        <div class="portlet box grey">
            <div class="row">
                <div class="col-lg-8">Clients</div>
                <div class="col-lg-4">
                    <button id="addclient" class="btn btn-md btn-success"   data-toggle="modal" data-target="#create"><b><i class="fas fa-plus"></i> Ajouter un Client</b></button>
                </div>
            </div>
        </div>

        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th style="width:20%">Nom</th>
                <th style="width:15%">Groupe</th>
                <th style="width:15%">Pays</th>
                <th style="width:30%">Nature</th>
                <th style="width:10%"><small>Lang 1</small></th>
                <th style="width:10%"><small>Lang 2</small></th>
              </tr>
            <tr>
                <th style="width:20%">Nom</th>
                <th style="width:15%">Groupe</th>
                <th style="width:15%">Pays</th>
                <th style="width:30%">Nature</th>
                <th style="width:10%">Lang 1</th>
                <th style="width:10%">Lang 2</th>
             </tr>
            </thead>
            <tbody>
            <?php   $listen=array(0=>'',-1=>'All',1=>'Assistance / Assurance',2=>'Avionneur',3=>'Pétrolier / apparenté',4=>'Clinique',5=>'Agence de voyage / Hôtel');
            ?>
            @foreach($clients as $client)

                <tr>
                    <td style="width:20%"><a href="{{action('ClientsController@view', $client['id'])}}" >{{$client->name}}</a></td>
                    <td style="width:15%"><?php $groupeid= $client['groupe']; echo ClientsController::GroupeById($groupeid);?> </td>
                    <td style="width:15%"><small>{{$client->pays}}</small></td>
                    <td style="width:20%">
<?php  $nature = $client['nature'];
                      //echo  $listen[1];
 if (strlen($nature)==1){
         echo '<small>'.$listen[$nature].'</small>';

 }else{
       $pieces = explode(",", $nature);
       foreach ($pieces as $n )
         {
        echo $listen[intval($n)].'   ';
        }

 }

                      ?>
                    </td>
                    <td style="width:10%"><small>{{$client->langue1}}</small></td>
                    <td style="width:10%"><small>{{$client->langue2}}</small></td>
					
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
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouveau Client</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <form method="post" >
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="type">Nom :</label>
                                <input class="form-control" type="text" id="name" />

                            </div>

                            <div class="form-group">
                                <label for="type">Pays :</label>
                                <input class="form-control" type="text" id="pays" />
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
                var name = $('#name').val();
                var pays = $('#pays').val();
                if ((name != '')&&(pays != '') )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('clients.saving') }}",
                        method:"POST",
                        data:{name:name,pays:pays, _token:_token},
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