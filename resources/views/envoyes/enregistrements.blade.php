@extends('layouts.mainlayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

<?php  use App\Http\Controllers\DossiersController;// use DB;
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
Use App\Adresse;
Use App\USer;
?>

@section('content')
    <style>
        .uper {
            margin-top: 10px;
        }
    </style>
  <div class="portlet box grey">
            <div class="row" style="align:right;">
                
               
               <a class="btn btn-default" id="recherchertp" href="{{url('/envoyes/enregistrements')}}"> Tous </a>
                    <a class="btn btn-default" id="recherchertp" href="{{url('/envoyes/enregistrementsdispatch')}}"> Dispatchés</a>
                    <a class="btn btn-default" id="recherchertp" href="{{url('/envoyes/enregistrementsnondispatch')}}">Non Dispatchés</a>
               
            </div>
        </div>
    <div class="uper">
        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                 <th style="width:10%;max-width:80px">Date</th>
                <th style="width:10%;;max-width:100px">Appelant</th>
                 <th style="width:10%;;max-width:100px">Appelé</th>
             <!--   <th style="width:45%;max-width:250px;">Contenu</th>!-->
                <th style="width:8%;;max-width:80px">Durée</th>
               <!-- <th style="width:8%;;max-width:80px ">Dossier</th>!-->
               <th style="width:8%;;max-width:80px">Sujet</th>
                <th  class="no-sort" style="width:8%;max-width:60px"></th>
             </tr>
            <tr>
                 <th style="width:10%;max-width:80px">Date</th>
                <th style="width:10%;;max-width:100px">Appelant</th>
                 <th style="width:10%;;max-width:100px">Appelé</th>
                <!--<th id="colmn3" style="width:30%;;max-width:150px;">Contenu</th>!-->
                <th style="width:8%;;max-width:80px;">Durée</th>
               <!-- <th style="width:8%;;max-width:80px; ">Dossier</th>!-->
                 <th style="width:8%;;max-width:80px">Sujet</th>
                <th id="colmn6" class="no-sort" style="width:8%;;max-width:60px"></th>
             </tr>
            </thead>
            <tbody>
			<?php  //$enregs= DB::table('enregistrements')->get();
 function convert($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}
			?>
            <?php   foreach($enregs as $enreg){ 
                ?>

                 <?php
              $adressecomm=Adresse::where("champ",$enreg->emetteur)->first();
              $usercom=User::where("id",$enreg->par)->first();
              ?>
                <tr> 
                     <td style="width:10%;font-size:12px;width:10%;max-width:80px"><?php  echo  date('d/m/Y H:i', strtotime($enreg->reception)) ; ?></td>
                    <td  style="width:10%;font-size:12px;max-width:100px;overflow:hidden;  text-overflow: ellipsis;"><?php echo $enreg->emetteur." (".$usercom['prenom']." ".$usercom['nom'].")"; ?></td>
                      <td  style="width:10%;font-size:12px;max-width:100px;overflow:hidden;  text-overflow: ellipsis;"><?php echo $enreg->destinataire." (".$adressecomm['name']." ".$adressecomm['lastname'].")"; ?></td>

                 <!--    <td  style="width:30%;font-size:12px;;max-width:150px;float:left    "         >
		 <audio controls>
  <source src="<?php   //echo  URL::asset('storage'.$enreg->path) ; ?>" type="audio/wav">
 Your browser does not support the audio element.
</audio></td>!-->
                    <td  style="width:8%;font-size:12px;;max-width:80px   "  ><?php    echo convert($enreg->duration) ; ?></a></td>
                    <!--<td  style="width:8%;font-size:12px;;max-width:80px "><?php // echo DossiersController::RefDossierById($enreg['dossier']).' - '.DossiersController::FullnameAbnDossierById($enreg['dossier']);?></td>!-->
                     <td  style="width:8%;font-size:12px;;max-width:80px   "  ><a   href="<?php echo $urlapp.'/envoyes/view/',$enreg->id?>"  >
                        <?php    echo $enreg->sujet ; ?></a></td>
                    <td stle=";max-width:60px">
                        @can('isAdmin')
                            <a  href="" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                <span class="fa fa-fw fa-trash-alt"></span>
                            </a>
                        @endcan

                    </td>
                </tr>
            <?php  }   ?>
            </tbody>
        </table>
    </div>
@endsection

<style>#colmn6 input,#colmn3 input{display:none;}</style>


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
                //"aaSorting": [],
				order:[],
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
        });
    </script>
@stop