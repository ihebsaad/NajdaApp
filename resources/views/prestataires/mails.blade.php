@extends('layouts.fulllayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

<link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />

 <link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

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
                <div class="col-lg-6"><h4>Liste des intervenants</h4></div>
                <div class="col-lg-6">
                    <button type="submit" id="buttModif" class="  btn btn-success  btn-lg"> Envoyer </button>

                </div>
            </div>
        </div>
      </div>



 <!-- table-->
  <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th  class="no-sort" style="width:15%"><label class="check "><b>Tous </b><input type="checkbox" onclick='selectall()' id="casetout" name="casetout" value="">   <span class="checkmark"></span></label></th>
                <th style="width:15%">Prestataire</th>
               <th style="width:20%;font-size:14px;">Type de prestations</th>
                <th style="width:10%">Gouvernorats</th>
                <th style="width:10%">Ville</th>
               <th style="width:20%">Spécialités</th>
<th style="width:20%">Statut</th>
             </tr>
            <tr style="font-size:14px;">
                <th style="width:15%" class="no-sort" ></th>
                <th style="width:15%">Prestataire</th>
                <th style="width:20%">Type de prestation</th>
                <th style="width:10%">Gouvernorats</th>
                <th style="width:10%">Ville</th>
               <th style="width:20%">Spécialités</th>
<th style="width:20%">Statut</th>

            </tr>
            </thead>
            <tbody>
             <?php if(isset($prests)) {$prestataires = $prests ;} ?>
            @foreach($prestataires as $prestataire)
                <?php $id= $prestataire->id ;$ville='';
                if($prestataire->ville !=''){$ville=$prestataire->ville;}else{


                     $villeid=intval($prestataire->ville_id );

                }

                $gouvs=  PrestatairesController::PrestataireGouvs($id);
                $typesp=  PrestatairesController::PrestataireTypesP($id);
                 $specs=  PrestatairesController::PrestataireSpecs($id);
                ?>

                <tr>
                    <td style="width:15%"><label class="check " style="cursor:pointer">  <input   class="checkbox" type="checkbox" id="pr-<?php echo $prestataire->id;   ?>" name="casedossier" value=" "> <span class="checkmark"></span> <?php echo $prestataire['name'].' '.$prestataire['prenom'] ; ?> </label></td>
                    <td style="font-size:14px;width:15%"><a href="{{action('PrestatairesController@view', $id)}}" ><?php echo ' <b> '. $prestataire->civilite .' '. $prestataire->name .'</b> '.$prestataire->prenom; ?></a></td>
                    <td style="font-size:12px;width:20%"><?php     foreach($typesp as $tp){echo PrestatairesController::TypeprestationByid($tp->type_prestation_id).',  ';}?></td>
                    <td style="font-size:12px;width:10%"><?php foreach($gouvs as $gv){echo PrestatairesController::GouvByid($gv->citie_id).',  ';}?></td>
                    <td style="font-size:12px;width:10%"><?php echo $ville; ?></td>
                    <td style="font-size:12px;width:15%"><?php foreach($specs as $sp){echo  PrestatairesController::SpecialiteByid($sp->specialite).',  ';}?></td>
<td  ><?php if ($prestataire->annule ==0){echo 'Actif';}else{echo 'Désactivé';} ?></td>

                </tr>
            @endforeach
             </tbody>
        </table>
    </div>



    <?php
    use \App\Http\Controllers\UsersController;
     $users=UsersController::ListeUsers();

    $CurrentUser = auth()->user();

    $iduser=$CurrentUser->id;

    ?>






    <!-- Modal -->
    <div class="modal fade" id="sendmails"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampl">Envoyer des Emails</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">



                        <form  enctype="multipart/form-data" id="theform" method="POST" action="{{action('EmailController@sendallgroup')}}"    onsubmit="return checkForm(this);"  >
                            {{ csrf_field() }}


                            <input id="envoye" type="hidden" class="form-control" name="envoye"  value="" />
                            <input id="brsaved" type="hidden" class="form-control" name="brsaved"  value="0" />


                            <?php  $from='24ops@najda-assistance.com';


                            ?>
                            <input type="hidden"   name="from" id="from" value="<?php echo $from; ?>" />
                            <input type="hidden"   name="destinataire"   value="prestataires" />

                            <select id="liste" name="liste[]"  multiple  style="display:none; "  >
                            </select>

                            <div class="form-group" style="margin-top:10px;">
                                <div id="autres" class="row"  style="display:none " >
                                    <div  class="row"  style="margin-bottom:10px" >
                                        <div class="col-md-2">
                                            <label for="cc">CC:</label>
                                        </div>
                                        <div class="col-md-10">
                                            <select id="cc" style="width:100%"   class="itemName form-control" name="cc[]" multiple   >
                                                <option></option>
                                                <option value="vat@medicmultiservices.com">vat@medicmultiservices.com</option>
                                                <option value="fact.vat-groupe@najda-assistance.com">fact.vat-groupe@najda-assistance.com</option>
                                                <option value="finances@medicmultiservices.com">finances@medicmultiservices.com</option>
                                                <option value="dirops@najda-assistance.com">dirops@najda-assistance.com</option>
                                                <option value="controle1@medicmultiservices.com">controle1@medicmultiservices.com</option>
                                                <option value="smq@medicmultiservices.com">smq@medicmultiservices.com</option>
                                                <option value="chef.plateau@najda-assistance.com">chef.plateau@najda-assistance.com</option>
                                                <option value="mohsalah.harzallah@gmail.com">mohsalah.harzallah@gmail.com</option>
                                                <option value="mahmoud.helali@gmail.com">mahmoud.helali@gmail.com</option>
                                            </select>
                                        </div>
                                    </div>


                                </div>
                            </div>



                            <div class="form-group">
                                <label for="sujet">Sujet :</label>


                                <input id="sujet" type="text" class="form-control" name="sujet" required value=""/>

                            </div>


                            <div class="form-group">
                                <label for="description">Description :</label>
                                <input id="description" type="text" class="form-control" name="description" id="description" required/>
                            </div>


                            <div class="form-group ">
                                <label for="contenu">Contenu:</label>
                                <div class="editor" >
                                    <textarea style="min-height: 280px;" id="contenu" type="text"  class="textarea tex-com" placeholder="Contenu de l'email ici" name="contenu" required  ></textarea>
                                </div>
                            </div>
                            <div class="form-group form-group-default">
                                <label>Attachements Externes <span style="color:red;">(la taille totale de fichiers ne doit pas dépasser 25 Mo)</span></label>
                                <input type="file" class="btn btn-danger fileinput-button kfile" name="vasplus_multiple_files[]" id="vasplus_multiple_files" multiple="multiple" style="padding:5px;"/>

                                <table class="table table-striped table-bordered" style="width:60%; border: none;" id="add_files">

                                    <tbody>

                                    </tbody>
                                </table>
                            </div>




                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button onclick=" resetForm(this.form);" id="SendBtn" type="submit"  name="myButton" class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
                </div>
            </div>
            </form>

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
                    },
'paging':false,

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


 $('#buttModif').click(function(){
     var _token = $('input[name="_token"]').val();
     var prestataires =new Array();
     j=0;
     var elements = document.getElementsByClassName('checkbox');
     for (var i = 0; i < elements.length; i++){
         //alert(elements[i].value);
         if(elements[i].checked)
         {

             prestataires[j]=getPrestId(elements[i]);
             j++;
         }
     }
     taille= prestataires.length;


     if(taille>0){
         for ( c = 0; c< taille; c++) {
             addItem(prestataires[c]);
         }

         $("#sendmails").modal('show');


     }
     else{ alert('sélectinnez des prestataires ! ');}

 });

 function checkForm(form) // Submit button clicked
 {

     form.myButton.disabled = true;
     form.myButton.value = "Please wait...";
     return true;
 }

 function resetForm(form) // Reset button clicked
 {
     form.myButton.disabled = false;
     form.myButton.value = "Submit";
 }

 function addItem(item){
     select = document.getElementById('liste');
     var opt = document.createElement('option');
     opt.value = item;
     opt.selected = true;

     opt.innerHTML = item;
     select.appendChild(opt);

 }

 function getPrestId(elm)
 {
     var idelm=elm.id;
     var idprest=idelm.slice(3);
     return(idprest);
 }


 function selectall(){
     var elements = document.getElementsByClassName('checkbox');
     var cas=document.getElementById('casetout');
     //test si on a plusieur ligne
     if(elements.length>0){
         if (cas.checked){
             for (var i=0; i<elements.length;i++){
                 elements[i].checked=true;
             }
         }
         else{
             for (var i=0; i<elements.length;i++){
                 elements[i].checked=false;
             }
         }
     }


 }

 </script>

@stop
