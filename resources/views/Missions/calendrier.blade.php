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
        <div class="portlet box grey">
             <div class="row">
                <div class="col-lg-8"> <h4>Calendrier de missions </h4></div>
                <div class="col-lg-4">
                   <!-- <button id="addfolder" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createfolder"><b><i class="fas fa-folder-plus"></i> Créer un Dossier</b></button>-->
             

                </div>
            </div>
        </div>

        <!-- debut recherche avancee sur dossiers-->


      <div class="portlet box blue">
               <div  style="background-color:#4fc1e9; height: 45px; margin-bottom: 0px; padding: 2px;">
                
                   <h4 style="cursor:pointer"  id="search">  &nbsp;<strong> <i class="fa fa-search"></i> &nbsp; Recherche de missions plateau :</strong></h4>
                    
                </div>
            <div class="portlet-title" style="margin-top: 0px; padding-top: 0px;">
                
            </div>
            <div class="portlet-body"  id="searchbox"    >
                <form accept-charset="utf-8" id="searchDossierform" action="{{route('recherchemissions.avancee')}}">
                    <div class="row">

                        <div class="col-md-4">
                            
                            <div class="form-group ">
                                <label >Saisissez une date ou une plage de dates :</label>
                                <!--<div id="reportrange" class="form-control btn default" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100% ;">
                                    <i class="fa fa-calendar"></i>
                                    <span> </span>
                                    <b class="fa fa-angle-down"></b>
                                </div>-->
                                <input type="text" name="daterange" class="form-control" value="" />
                                <input type="hidden" name="date_debut" id="date_debut_recherche" value="" />
                                <input type="hidden" name="date_fin" id="date_fin_recherche" value="" />
                            </div>
                        </div>
                       <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputError" class="control-label" >N° séance</label>
                                
                               
                                        <select id="seanceid" name="seanceid" class="form-control select2" >
                                            <option value="">Sélectionner</option>
                                            
                                                <option value="1"> séance 1</option>
                                                <option value="2"> séance 2</option>
                                                <option value="3"> séance 3</option>

                                        </select>
                            </div>
                        </div>-->

                   </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-circle blue pull-right" id="rechercher" type="submit" style="margin-top: 20px;margin-right:20px;">  Rechercher</button>
                        </div>
                    </div>
                </form>
            </div>

          
        </div>

       


            <table class="table table-striped" id="mytable" style="width:100%">
            <thead >
             <tr id="headtable">
                <th style="width:20%">Type Mission</th>
                <th style="width:20%">Extrait</th>
                 <th style="width:25%">Dossier</th>
                 <th style="width:20%">Séance</th>
                 <th style="width:20%">Statut </th>
                 <th style="width:20%">Date </th>
              </tr>
            <tr>
                <th style="width:20%">Type Mission</th>
                <th style="width:20%">Extrait</th>
                 <th style="width:25%">Dossier</th>
                 <th style="width:20%">Séance</th>
                  <th style="width:20%">Statut</th>

                 <th style="width:20%">Date</th>
            </tr>
            </thead>
            <tbody>
            @if($missions)

            @foreach($missions as $do)

            @if($do->statut_courant!="endormie")
                <tr>
                    <td style="width:20%"><?php echo '<small>'.$do->nom_type_miss.'</small>';?></td>
                    <td style="width:25%">
                        <?php echo '<small>'.$do->titre .'</small>';?>
                    </td>
                    <td style="width:20%"><a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic  ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}" >Fiche<i class="fa fa-file-txt"/></a></td>
                    <?php  //$deb_seance_1=(new \DateTime())->format('08:00:00');
                             $deb_seance_1=strtotime('08:00:00');
                             $fin_seance_1= strtotime('14:59:00');
                            // dd($deb_seance_1.' '.$fin_seance_1);
                         // $deb_seance_1= \Date("H:i:s",strtotime('Y-m-d 08:00:00'));
                         //dd($deb_seance_1);
                            //$fin_seance_1=(new \DateTime())->format('Y-m-d 15:00:00');

                            $deb_seance_2=strtotime('15:00:00');
                            $fin_seance_2=strtotime('22:59:00');

                           // $deb_seance_3=(new \DateTime())->format('Y-m-d 23:00:00');
                            //$fin_seance_3=(new \DateTime())->modify('+1 day')->format('Y-m-d 08:00:00');
 
                            //$format = "H:i:s";
                               // dd(strtotime($do->date_deb));
                           // $dateMiss = \DateTime::createFromFormat($format,$do->date_deb); 
                            $dateMiss =\Date("H:i:s",strtotime($do->date_deb));
                            $dateMiss=strtotime($dateMiss);
                           // dd($dateMiss);

                            if($do->statut_courant!="active" && $do->statut_courant!="deleguee") {

                                    if($dateMiss>=$deb_seance_1 &&  $dateMiss <=$fin_seance_1 ) { 
                                           echo '<td style="width:20%"> séance 1</td>';

                                             }elseif ($dateMiss>=$deb_seance_2 &&  $dateMiss <=$fin_seance_2 ){ 

                                                echo '<td style="width:20%"> séance 2</td>';

                                               }else { // seance 3

                                                 echo '<td style="width:20%"> séance 3</td>';

                                              } 

                                          }else
                                          {
                                            echo '<td style="width:20% ;color:red;"> Maintenant</td>';


                                          }



                                              ?>

                     <td style="width:20%">
                      
                      @if($do->statut_courant=="deleguee")
                      {{"déléguée"}}
                      @endif
                      @if($do->statut_courant=="reportee")
                      {{"reportée"}}
                      @endif
                       @if($do->statut_courant=="active")
                      {{"active"}}
                      @endif
                      @if($do->statut_courant=="endormie")
                      {{"endormie"}}
                      @endif

                    </td>

 
                    <td style="width:20%"> 

                     {{$do->date_deb}}                    

                    </td>



                </tr>

                @else
                <?php $actions=App\ActionEC::where('mission_id',$do->id)->where('statut','reportee')->orWhere('statut','rappelee')->get(); ?>
                 @foreach($actions as $aa) {{--  debut action--}}

                     <tr>
                    <td style="width:20%"><?php echo '<small>'.$do->nom_type_miss.'</small>';?></td>
                    <td style="width:25%">
                        <?php echo '<small>'.$do->titre .'</small>';?>
                    </td>
                    <td style="width:20%"><a href="{{action('DossiersController@view', $do->dossier_id)}}" ><?php echo App\Dossier::where('id',$do->dossier_id)->first()->reference_medic  ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->dossier_id )}}" >Fiche<i class="fa fa-file-txt"/></a></td>
                    <?php  //$deb_seance_1=(new \DateTime())->format('08:00:00');
                             $deb_seance_1=strtotime('08:00:00');
                             $fin_seance_1= strtotime('14:59:00');
                            // dd($deb_seance_1.' '.$fin_seance_1);
                         // $deb_seance_1= \Date("H:i:s",strtotime('Y-m-d 08:00:00'));
                         //dd($deb_seance_1);
                            //$fin_seance_1=(new \DateTime())->format('Y-m-d 15:00:00');

                            $deb_seance_2=strtotime('15:00:00');
                            $fin_seance_2=strtotime('22:59:00');

                           // $deb_seance_3=(new \DateTime())->format('Y-m-d 23:00:00');
                            //$fin_seance_3=(new \DateTime())->modify('+1 day')->format('Y-m-d 08:00:00');
 
                            //$format = "H:i:s";
                               // dd(strtotime($do->date_deb));
                           // $dateMiss = \DateTime::createFromFormat($format,$do->date_deb); 
                            if($aa->statut=="reportee")
                                {$tt=$aa->date_report ;}
                            else{if($aa->statut=="rappelee")
                                {$tt=$aa->date_rappel ;}}
                            $dateMiss =\Date("H:i:s",strtotime($tt));
                            $dateMiss=strtotime($dateMiss);
                           // dd($dateMiss);


                                    if($dateMiss>=$deb_seance_1 &&  $dateMiss <=$fin_seance_1 ) { 
                                           echo '<td style="width:20%"> séance 1</td>';

                                             }elseif ($dateMiss>=$deb_seance_2 &&  $dateMiss <=$fin_seance_2 ){ 

                                                echo '<td style="width:20%"> séance 2</td>';

                                               }else { // seance 3

                                                 echo '<td style="width:20%"> séance 3</td>';

                                              } ?>

                     <td style="width:20%">
                                           
                      {{"endormie"}}
                     

                    </td>

 
                    <td style="width:20%"> 

                     <?php if($aa->statut=="reportee"){echo $aa->date_report ;}else{if($aa->statut=="rappelee"){echo $aa->date_rappel ;}} ?>                    

                    </td>



                </tr>



                 @endforeach {{--  fin action--}}
                @endif
            @endforeach
            @endif
            </tbody>
        </table>

            <!-- fin recherche avancee sur dossiers-->

         
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




        $('#add').click(function(){
            var _token = $('input[name="_token"]').val();

            var type_dossier = $('#type_dossier').val();
            var type_affectation = $('#type_affectation').val();
            var name = $('#subscriber_lastname').val();
            var lastname = $('#subscriber_name').val();
           // var entree = $('#entree_id').val();
            if ((type_dossier != '')&&(type_affectation != ''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.saving') }}",
                    method:"POST",
                    data:{type_dossier:type_dossier,type_affectation:type_affectation,name:name,lastname:lastname, _token:_token},
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
   
     // var start = moment("01/01/2015", "DD/MM/YYYY");

    var start= moment();
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
           'Demain': [moment().add(1, 'days'), moment().add(1, 'days')],
           '+ 7 jours': [moment(), moment().add(6, 'days')],
           '+ 15 jours': [moment(), moment().add(15, 'days')],
           '+ 30 jours': [moment(), moment().add(30, 'days')]
          
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