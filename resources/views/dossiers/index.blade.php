@extends('layouts.mainlayout')


    <style>
        .uper {
            margin-top: 20px;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
 <!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

@section('content')
    <?php use \App\Http\Controllers\DossiersController;     ?>
    <div class="uper">
        <div class="portlet box grey">
            <div class="modal-header">Dossiers</div>
        </div>
        <table class="table table-striped" id="mytable" style="width:100%">
            <thead >
            <tr id="headtable">
                <th style="width:20%">Référance</th>
                <th style="width:25%">Client</th>
                <th style="width:30%">Abonné</th>
                <th style="width:15%">Etat</th>
              </tr>
            <tr>
                <th style="width:20%">Référance</th>
                <th style="width:25%">Client</th>
                <th style="width:30%">Abonné</th>
                <th style="width:15%">Etat</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dossiers as $dossier)
                <tr>
                     <td style="width:20%"><a href="{{action('DossiersController@view', $dossier['id'])}}" >{{$dossier->reference_medic}}</a></td>
                    <td style="width:25%">
                        <?php $customer_id= $dossier['customer_id']; echo '<small>'. DossiersController::ClientById($customer_id).'</small>';?>
                    </td>
                    <td style="width:30%"><?php echo '<small>'.$dossier['subscriber_name'] .' '.$dossier['subscriber_lastname'] .'</small>';?></td>
                    <td style="width:15%"> {{$dossier->current_status}} </td>

                    </td>



                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection


@section('footer_scripts')
    <style>

        </style>
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
            $(this).html( '<input class="searchfield" type="text" />' );
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



    });

    </script>
@stop