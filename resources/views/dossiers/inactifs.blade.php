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
    <link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

@section('content')


    <?php use \App\Http\Controllers\DossiersController;
    use \App\Http\Controllers\EntreesController;

    ?>
    <div class="uper">
        <div class="portlet box grey">
             <div class="row">
                <div class="col-lg-8"> <h3 style="font-wight:bold">Liste des dossiers inactifs immobiles </h3></div>
                <div class="col-lg-4">
                 </div>
            </div>

        </div>


        <table class="table table-striped" id="mytable" style="width:100%">
            <thead >
            <tr id="headtable">
                <th style="width:20%">Référence</th>
                <th style="width:20%">Assuré</th>
                <th style="width:25%">Client</th>
            </tr>
            <tr>
                <th style="width:20%">Référence</th>
                <th style="width:20%">Assuré</th>
                <th style="width:25%">Client</th>
            </tr>
            </thead>
            <tbody>

            @foreach($dossiers as $dossier)
                <?php
                $style="";
               if($dossier->updatedmiss_at) {
                   // dd('xxxxxxxxxxxxxx');
                if( \App\Http\Controllers\DossiersController::checkImmobile3D($dossier->updatedmiss_at)== true    )
                   {$style="background-color:#fd9883;"; }
                  }

                ?>
                <tr  style="<?php echo $style;?>"><?php $statut=$dossier['current_status'];  $affecte=$dossier['affecte'];   ?>
                    <td style="width:20%; <?php echo $style;?>"><a href="{{action('DossiersController@view', $dossier['id'])}}" >{{$dossier->reference_medic}}</a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $dossier['id'])}}" >Fiche<i class="fa fa-file-txt"></a></td>
                     <td style="width:20%"><?php echo '<small>'.$dossier['subscriber_name'] .' '.$dossier['subscriber_lastname'] .'</small>';?></td>
                    <td style="width:25%">
                        <?php $customer_id= $dossier['customer_id']; echo '<small>'. DossiersController::ClientById($customer_id).'</small>';?>
                    </td>
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



    <style>.searchfield{width:100px;}
     </style>

    <script type="text/javascript">
    $(document).ready(function() {
      //  $('#searchbox').hide();


        $('#mytable thead tr:eq(1) th').each( function () {
            var title = $('#mytable thead tr:eq(0) th').eq( $(this).index() ).text();
            $(this).html( '<input class="searchfield" type="text" />' );
        } );

        var table = $('#mytable').DataTable({
            orderCellsTop: true,
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
            var type_dossier = $('#type_dossier').val();
            var type_affectation = $('#type_affectation').val();
            var affecte = $('#affecte').val();
            if ((type_dossier != '')&&(type_affectation != '')&&(affecte != ''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.saving') }}",
                    method:"POST",
                    data:{type_dossier:type_dossier,type_affectation:type_affectation,affecte:affecte, _token:_token},
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
   
      var start = moment("01/01/2015", "DD/MM/YYYY");
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
    $("#reference_medic1").select2();
    $("#nom_benef_search").select2();
    $("#pres_id_search").select2();
    $("#customer_id_search").select2();
    $("#current_status").select2();




 });


 $('#search').on('click',   function() {

     var   div=document.getElementById('searchbox');
     if(div.style.display==='none')
     {
         div.style.display='block';
     }
     else
     {
         div.style.display='none';
     }

 });

</script>


@stop