@extends('layouts.dispatchlayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />


<?php  $urlapp=env('APP_URL');

if (App::environment('local')) {
    // The environment is local
    $urlapp='http://localhost/najdaapp';
}
?>

@section('content')
    <style>
        .uper {
            margin-top: 10px;
        }
    </style>
    <div class="uper">

        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th style="width:6%">Type</th>
                <th style="width:10%;max-width:150px">Dest</th>
                <th style="width:10%">Date</th>
                <th style="width:10%;max-width:150px">Emetteur</th>
                <th style="width:35%">Sujet</th>
                <th class="no-sort" style="width:8%"></th>

                <!-- <th class="no-sort" style="width:8%" ></th>-->

            </tr>
            <tr>
                <th style="width:6%">Type</th>
                <th style="width:10%;;max-width:150px">Dest</th>
                <th style="width:10%">Date</th>
                <th style="width:10%;;max-width:150px">Emetteur</th>
                <th style="width:35%;;max-width:200px">Sujet</th>
                <th id="colmn6" class="no-sort" style="width:8%"></th>
                <!-- <th style="width:8%" id="colmn6"></th>-->
            </tr>
            </thead>
            <tbody>
            @foreach($entrees as $entree)
                <tr><?php $type=$entree['type'];?>
				      <td style="font-size:14px;width:6%"><?php if ($type=='email'){echo '<img width="20" src="'. $urlapp .'/public/img/email.png" />';} ?><?php if ($type=='fax'){echo '<img width="20" src="'. $urlapp .'/public/img/faxx.png" />';} ?><?php if ($type=='sms'){echo '<img width="20" src="'. $urlapp .'/public/img/smss.png" />';} ?> <?php if ($type=='phone'){echo '<img width="20" src="'. $urlapp .'/public/img/tel.png" />';} ?> <?php echo $entree['type']; ?></td>
                    <td  style="width:10%;font-size:12px;max-width:150px;overflow:hidden;  text-overflow: ellipsis;"><?php echo $entree['destinataire']; ?></td>
                    <td style="width:10%;font-size:12px;width:10%"><?php echo  date('d/m/Y', strtotime($entree['reception'])) ; ?></td>
                    <td  style="width:10%;font-size:12px;max-width:150px;overflow:hidden;  text-overflow: ellipsis;"><?php echo $entree['emetteur']; ?></td>
                    <td  style="width:35%;font-size:12px;max-width:200px;"><a <?php if($entree['viewed']==false) {echo 'style="color:#337085!important;font-weight:800;font-size:16px;"' ;} ?>  href="{{route('entrees.showdisp',['id'=> $entree['id']] )}}" ><?php echo $entree['sujet'] ; ?></a></td>
                    <td></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

<style>#colmn6 input{display:none;}</style>


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
              //  $(this).html( '<input class="searchfield" type="text" placeholder="'+title+'" />' );
                $(this).html( '<input class="searchfield" type="text"   />' );
            } );

            var table = $('#mytable').DataTable({
                "aaSorting": [],
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