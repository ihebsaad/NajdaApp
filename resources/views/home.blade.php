@extends('layouts.mainlayout')
    <script type="text/javascript" src="{{ asset('public/js/jquery-1.11.1.min.js') }}" ></script>
  <style>
  .my-custom-scrollbar {
position: relative;
height: 500px;
overflow: auto;
}
.table-wrapper-scroll-y {
display: block;
}
</style>
@section('content')

    <div class="row">
        <br><br>
        <h1> Bienvenue</h1>
    </div>
     <?php  if ($type == 'financier' || $type == 'bureau' || $type == 'admin' ) {   ?>
            <div class="row  pull-right">
             <div class="col-sm-2">
            <a href="{{ route('factures') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span  class="fas fa-lg fa-file-invoice"></span>
                <br>
            Factures
            </a>
              </div>
            </div>

    <div class="row ">



    <div class="col-md-12">
        <div class="card  ">
            <div class="card-header bg-danger " >
                <h3 class="card-title" style="color:white;padding:10px 10px 10px 10px">
                    <i class="fas    fa-warning"></i> Notifications financiers     </h3>

            </div>

                    <div class="form-group">
                        <table class="table table-striped" id='mytable' style="width:100%" >
                         <thead>
                         <tr id="headtable">
                             <th style="width:15%">Date</th><th style="width:30%">Dossier</th><th style="width:8%">Nb Factures</th><th style="width:15%">Statut</th><th style="width:10%">Facturé</th><th class="no-sort" style="width:10%">Traiter</th>
                         </tr>
                         <tr> 
                         <th class="input" style="width:15%">Date</th><th class="input"  style="width:30%">Dossier</th><th class="input"  style="width:8%">Nb Factures</th><th class="input"  style="width:15%">Statut</th><th class="input"  style="width:10%">Facturé</th><th  style="width:10%">Traiter</th>
                         </tr>
                         </thead>
                            <tbody>
                       <?php 
                       
                       foreach ($alertes as $alerte){
                       $dossier=  \App\Dossier::where('id',$alerte->id_dossier)->first();
                       
                       $abn= $dossier->subscriber_name. ' '.$dossier->subscriber_lastname ;
                       $statut= $alerte->statut;
                       $datea= date('d/m/Y H:i', strtotime($alerte->created_at)) ;
                       $facture= $alerte->facture; //if($facture!=1){$fact='<label style="color:white;padding:10px 10px 10px 10px" class="bg-danger">Non Facturé';}else{$fact='<label style="color:white;padding:10px 10px 10px 10px" class="bg-success">Facturé</label>';}
                       if($statut=='reouverture'){$stat='Re-Ouverture';}
                       if($statut=='ferme'){$stat='Fermeture';}
                       if($statut=='sanssuite'){$stat='Fermeture <small>Sans Suite</small>';}
                      $count= \App\Facture::where('iddossier',$dossier->id)->count() ; 

                       ?>
                       <tr><td style="width:15%"><?php   echo $datea; ?></td> 
                       <td style="width:30%"> <a href="{{action('DossiersController@view', $alerte->id_dossier)}}" ><?php echo $alerte->ref_dossier . ' '.$abn; ?> </a></td>
                       <td style="width:8%"><?php echo $count; ?> </td>
                       <td style="width:15%"><?php echo $stat; ?> </td>
                       <td style="width:10%">

                                     <div class="radio" id="uniform-actif">
                           <label><span class="checked">
                            <input  class="actus-<?php echo $alerte->id;?>"  type="checkbox"    id="actus-<?php echo $alerte->id;?>"    <?php if ($facture ==1){echo 'checked'; }  ?>  onclick="changing(this,'<?php echo $alerte->id; ?>' );"      >
                        </span> Facturé</label>
                                     </div>
                            </td><td style="width:8%;text-align: center">
                                <a   href="{{action('HomeController@traiter', $alerte['id'])}}" class="btn btn-sm btn-success btn-responsive" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom"  data-original-title="Traiter">
                                    <i class="fa fa-fw fa-check"></i>
                                </a>

                            </td></tr>
                        <?php } ?>
                            </tbody> </table>
                    </div>



    </div>

    </div>


    </div>

    <div class="card-header bg-danger " >
                <h3 class="card-title" style="color:white;padding:10px 10px 10px 10px">
                    <i class="fas    fa-warning"></i> Dossiers immobiles n'ayant pas d'activités depuis 3 jours     </h3>

            </div>

      
 <div class="row">
       <div class="col-md-12">
      <?php $dossiersimm= App\DossierImmobile::get(); ?>       
           
        <input class="form-control" id="myInput" type="text" placeholder="Recherche..">
        <br>

        <div class="table-wrapper-scroll-y my-custom-scrollbar">

        <table class="table table-bordered table-striped mb-0" >
       <!--  <table class="table table-striped" id="mytablef" > -->
            <thead>
            <tr style="background-color: #33B2FF">
                <th style="width:20%">Référence</th>
                <th style="width:20%">Nom Client </th>
                <th style="width:20%"> Mail Client</th>
                <th style="width:20%"> Message auto envoyé</th>
                <th style="width:20%"> Date envoi</th>
               

            </tr>
           
            </thead>
            <tbody id="myTablekk">                  

            @foreach($dossiersimm as $dossier)
               
                <tr>
                    <td style="width:20%;"><a href="{{action('\App\Http\Controllers\DossiersController@view', $dossier['id'])}}" >{{$dossier->reference_doss}}</a> <a style="color:#a0d468" href="{{action('\App\Http\Controllers\DossiersController@fiche', $dossier['id'])}}" >Fiche<i class="fa fa-file-txt"></a></td>
                     <td style="width:20%"><?php echo '<small>'.$dossier->client_name.'</small>';?></td>
                    <td style="width:20%"><?php echo '<small>'.$dossier->client_adresse.'</small>';?></td>
                    <td style="width:20%"><?php echo '<small>'.$dossier->mail_auto_envoye.'</small>';?></td>
                    <td style="width:20%"><?php echo '<small>'.$dossier->date_envoi_mail.'</small>';?></td>

                </tr>
            @endforeach
            </tbody>
        </table>
     
             </div>
         </div>
     </div> 
    <?php   } // fin if admin financier,,,
         ?>


   <script>
  $(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTablekk tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>


    <script>

        function changing(elm,actus) {
            var champ=elm.id;

            var val =document.getElementById('actus-'+actus).checked==1;

            if (val==true){val=1;}
            else{val=0;}
            //if ( (val != '')) {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('home.updating') }}",
                method: "POST",
                data: {actus:actus , champ:champ ,val:val, _token: _token},
                success: function (data) {
                    $('.actus-'+actus).animate({
                        opacity: '0.3',
                    });
                    $('.actus-'+actus).animate({
                        opacity: '1',
                    });

                }
            });
            // } else {

            // }
        }


    </script>
 
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
 

            $('#mytable thead tr:eq(1) th').each( function () {
                var title = $('#mytable thead tr:eq(0) th .input').eq( $(this).index() ).text();
                $(this).html( '<input class="searchfield" type="text" style="width:60px"  />' );
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
            
            
      });
 
    </script>
     
     @endsection