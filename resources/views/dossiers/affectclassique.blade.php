@extends('layouts.fulllayout')


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
//echo 'Max Id : '.DossiersController::GetMaxIdBytypeN();
    ?>
    <div class="uper">

        <!-- debut recherche avancee sur dossiers-->

<!--
      <div class="portlet box blue">
               <div  style="background-color:#4fc1e9; height: 45px; margin-bottom: 0px; padding: 2px;">
                
                   <h4 style="cursor:pointer"  id="search">  &nbsp;<strong> <i class="fa fa-search"></i> &nbsp;Recherche avancée </strong></h4>
                    
                </div>
            <div class="portlet-title" style="margin-top: 0px; padding-top: 0px;">
                
            </div>
            <div class="portlet-body"  id="searchbox"    >
                <form accept-charset="utf-8" id="searchDossierform" action="{{route('page_recherche.avancee')}}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputError" class="control-label" >Référence Dossier</label>

                               
                                        <select id="reference_medic1" name="reference_medic1" class="form-control select2" >
                                            <option value="">Sélectionner</option>
                                             @foreach(App\Dossier::get() as $c)

                                                <option value="{{$c->reference_medic}}">{{$c->reference_medic}}</option>

                                              @endforeach



                                        </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Etat </label>
                                <select id="current_status" name="current_status" class="form-control select2" >
                                    <option value="">Sélectionner.....</option>
                                    <option value="Cloture">Clos</option>
                                    <option value="En cours">En cours</option>
                                    <option value="Cloture transport">Clos transport</option>
                                    <option value="En cours transport">En cours transport</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Client </label>
                                <select id="customer_id_search" name="customer_id_search" class="form-control js-example-placeholder-single select2" >
                                    <option value="">Tous</option>
                                      @foreach(App\Client::get() as $c)

                                         <option value="{{$c->id}}">{{$c->name}}</option>

                                      @endforeach
                                                                    
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Assuré </label>

                            <select id="nom_benef_search" name="nom_benef_search" class="form-control select2" >
                                            <option value="">Sélectionner</option>
                                    @foreach(App\Dossier::distinct()->whereNotNull('subscriber_name')->get(['subscriber_name' , 'subscriber_lastname' , 'vehicule_immatriculation' ]) as $c) 

                                                <option value="{{$c->subscriber_name}} {{$c->subscriber_lastname}} {{$c->vehicule_immatriculation}}">{{$c->subscriber_name}} {{$c->subscriber_lastname}}  {{$c->vehicule_immatriculation}} </option>

                                    @endforeach

                             </select>
                        </div>
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
                            
                            <div class="form-group ">
                                <label >Date de création</label>

                                <input type="text" name="daterange" class="form-control" value="" />
                                <input type="hidden" name="date_debut" id="date_debut_recherche" value="" />
                                <input type="hidden" name="date_fin" id="date_fin_recherche" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-circle blue pull-right" id="rechercher" type="submit" style="margin-top: 20px;margin-right:20px;">  Rechercher</button>
                        </div>
                    </div>
                </form>
            </div>

          
        </div>
-->
        <div class="row" style="width:800px">
            <div class="col-md-6">
                <div class="form-group">
                     <select class="form-control select2" name="agent_name" id="agent_name">
                        <option value="">sélectionner l'agent</option>

                        @foreach(App\User::get() as $u)

                          <?php  if($u->statut== 1 &&  $u->user_type!= 'financier' &&  $u->user_type!= 'admin' && $u->user_type!= 'bureau' && $u->id!= 2  ) {  ?> <option value="{{$u->id}}">{{$u->name}} {{$u->lastname}} </option> <?php } ?>

                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <button type="submit" id="buttModif" class="  btn btn-success  btn-lg"> Affecter </button>
                </div>
            </div>
        </div>

         <?php if (isset($datasearch)) { ?>
 

            <table class="table table-striped" id="mytable" style="width:100%">
            <thead >
             <tr id="headtable">
                <th style="width:20%">Référence</th>
                <th style="width:20%">Assuré</th>
                 <th style="width:25%">Client</th>
                 <th style="width:20%">Agent</th>
              </tr>
            <tr>
                <th style="width:20%">Référence</th>
                <th style="width:20%">Assuré</th>
                <th style="width:25%">Client</th>
                <th style="width:20%">Agent</th>
            </tr>
            </thead>
            <tbody>

            @foreach($datasearch as $do)
                <tr><?php $statut=$do->current_status;  $affecte=$do->affecte;   ?>
                    <td style="width:20%"><a href="{{action('DossiersController@view', $do->id )}}" >{{$do->reference_medic}}</a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->id )}}" >Fiche<i class="fa fa-file-txt"/></a></td>
                    <td style="width:20%"><?php echo '<small>'.$do->subscriber_name .' '.$do->subscriber_lastname .'</small>';?></td>
                    <td style="width:25%">
                        <?php $customer_id= $do->customer_id ; echo '<small>'. DossiersController::ClientById($customer_id).'</small>';?>
                    </td>
                    <td style="width:20%"> <?php if($statut=='Cloture'){echo 'Clôturé';} else {
                    if($affecte==0 or ($affecte=='') ){echo '<span style="color:red">Non Affecté !</span>';}else {
                        echo app('App\Http\Controllers\UsersController')->ChampById('name', $affecte);
                    }
                    }
                     ?> </td>

                    </td>



                </tr>
            @endforeach
            </tbody>
        </table>

            <!-- fin recherche avancee sur dossiers-->

         <?php }else {if (isset($dossiers)) { ?>
        <table class="table table-striped" id="mytable" style="width:100%">
            <thead >
            <tr id="headtable">
                <th  class="no-sort" style="width:5%"><label class="check "><b>Tous </b><input type="checkbox" onclick='selectall()' id="casetout" name="casetout" value="">   <span class="checkmark"></span></label></th>
                <th style="width:15%">Référence</th>
                <th style="width:20%">Assuré</th>
                <th style="width:25%">Client</th>
                <th style="width:20%">Agent</th>
                <th style="width:10%">Statut</th>
                <th style="width:10%">Type</th>
              </tr>
            <tr>
				<td class="no-sort"style="width:5%"></td>
                <th style="width:15%">Référence</th>
                <th style="width:20%">Assuré</th>
                <th style="width:25%">Client</th>
                <th style="width:20%">Agent</th>
                <th style="width:10%">Statut</th>
                <th style="width:10%">Type</th>
            </tr>
            </thead>
            <tbody>

            @foreach($dossiers as $dossier)
                <tr><?php $etat=$dossier['statut']; $statut=$dossier['current_status'];  $affecte=$dossier['affecte'];   ?>
                    <td style="width:5%"><label class="check "><small><?php echo $dossier['reference_medic']?></small> <input   class="checkbox" type="checkbox" id="doss-<?php echo $dossier->id;   ?>" name="casedossier" value=" "> <span class="checkmark"></span></label></td>
					<td style="width:15%"><a href="{{action('DossiersController@view', $dossier['id'])}}" >{{$dossier->reference_medic}}</a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $dossier['id'])}}" >Fiche<i class="fa fa-file-txt"></a></td>
                    <td style="width:20%"><?php echo '<small>'.$dossier['subscriber_name'] .' '.$dossier['subscriber_lastname'] .'</small>';?></td>
                    <td style="width:25%">
                        <?php $customer_id= $dossier['customer_id']; echo '<small>'. DossiersController::ClientById($customer_id).'</small>';?>
                    </td>
                    <td style="width:20%"> <?php if($statut=='Cloture'){echo 'Clôturé';} else {
                    if($affecte==0 or ($affecte=='') ){echo '<span style="color:red">Non Affecté !</span>';}else {
                        if($etat=='5'){ echo '<b>'; } echo   app('App\Http\Controllers\UsersController')->ChampById('name', $affecte).' '. app('App\Http\Controllers\UsersController')->ChampById('lastname', $affecte);  if($etat=='5'){ echo '</b>'; }
                    }
                    }
                     ?> </td>
                    <td style="width:10%"><?php if($dossier['current_status']=='inactif') {echo $dossier['current_status'].' ('.$dossier['sub_status'].')';}else {echo $dossier['current_status'];}?></td>
                    <td style="width:10%"> <?php if($etat=='5'){echo '<b>Manuel</b>';} else {
                    if($etat=='2'){echo 'Automatique';}
                    }
                     ?> </td>
  
                </tr>
				
 
            @endforeach
            </tbody>
        </table>


    <?php  } }?>
    </div>



    <?php use \App\Http\Controllers\UsersController;
    $users=UsersController::ListeUsers();

    $CurrentUser = auth()->user();

    $iduser=$CurrentUser->id;

    ?>

    <!-- Modal -->
    <div class="modal fade" id="createfolder"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="card-body">

                       <form method="post" >
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="type">Type :</label>
                            <select   id="type_dossier" name="type_dossier" class="form-control js-example-placeholder-single">
                                <option   value="Medical">Medical</option>
                                <option   value="Technique">Technique</option>
                                <option   value="Mixte">Mixte</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="type">Type d'affectation :</label>
                            <select id="type_affectation" name="type_affectation" class="form-control js-example-placeholder-single"  >
                                <option  value="Najda">Najda</option>
                                <option   value="VAT">VAT</option>
                                <option  value="MEDIC">MEDIC</option>
                                <option   value="Transport MEDIC">Transport MEDIC</option>
                                <option   value="Transport VAT">Transport VAT</option>
                                <option  value="Medic International">Medic International</option>
                                <option   value="Najda TPA">Najda TPA</option>
                                <option   value="Transport Najda">Transport Najda</option>
                                <option  value="X-Press">X-Press</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="type">Prénom de l'assuré :</label>
                            <input type="text" id="subscriber_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="type">Nom de l'assuré :</label>
                            <input type="text" id="subscriber_lastname" class="form-control">
                        </div>


                        <input type="hidden" value="nouveau" name="statdoss">


                         </form>
                    </div>





                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" id="add" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

<style>

    /* The check */
    .check {
        display: block;
        position: relative;
        padding-left: 25px;
        margin-bottom: 12px;
        padding-right: 15px;
        cursor: pointer;
        font-size: 18px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .check input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 3px;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #fff ;
        border-color:#5D9CEC;
        border-style:solid;
        border-width:2px;
    }



    /* When the checkbox is checked, add a blue background */
    .check input:checked ~ .checkmark {
        background-color: #fff  ;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .check input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .check .checkmark:after {
        left: 5px;
        top: 1px;
        width: 5px;
        height: 10px;
        border: solid ;
        border-color:#5D9CEC;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
  </style>


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
             pageLength : 50,
             orderCellsTop: true,
			  order:[],

          //  dom: 'Bflrtip',
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
    $("#agent_name").select2();




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

    <script type="text/javascript">
        $('#buttModif').click(function(){
            var agent = $('#agent_name').val();
            var _token = $('input[name="_token"]').val();
            var type = $('#type').val();
             var dossiers =new Array();
            j=0;
            var elements = document.getElementsByClassName('checkbox');
            for (var i = 0; i < elements.length; i++){
                //alert(elements[i].value);
                if(elements[i].checked)
                {


                    dossiers[j]=getDossierId(elements[i]);
                     j++;
                }
            }
            // alert(dossiers);
            dossiers=JSON.stringify(dossiers);

            if(agent > 0)
            {
            $.ajax({
                url: "{{ route('dossiers.attribution2') }}",
                method: "POST",
                dataType:"json",
                data: {  dossiers:dossiers  ,agent:agent,type:type, _token: _token},
                success: function ( ) {
                    window.location.href = window.location.href;

                }
            });

            }else{alert("Sélectionner l'agent !");}
        });



        function getDossierId(elm)
        {
            var idelm=elm.id;
            var dossid=idelm.slice(5);
            return(dossid);
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