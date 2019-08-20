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
            <div class="row">
                <div class="col-lg-8">liste des Dossiers</div>
                <div class="col-lg-4">
                 <!--   <button id="addfolder" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createfolder"><b><i class="fas fa-folder-plus"></i> Créer un Dossier</b></button>-->
                </div>
            </div>
        </div>

        <!-- debut recherche avancee sur dossiers-->


      <div class="portlet box blue">
               <div  style="background-color:#4fc1e9; height: 45px; margin-bottom: 0px; padding: 2px;">
                
                   <h4>  &nbsp;<strong> <i class="fa fa-search"></i> &nbsp;Recherche avancée </strong></h4>
                    
                </div>
            <div class="portlet-title" style="margin-top: 0px; padding-top: 0px;">
                
            </div>
            <div class="portlet-body">
                <form accept-charset="utf-8" id="searchDossierform" action="{{route('page_recherche.avancee')}}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputError" class="control-label" >Référence </label>
                                <input type="text" id="reference_medic1" name="reference_medic1" class="form-control" placeholder="Référence....">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Etat </label>
                                <select id="current_status" name="current_status" class="form-control js-example-placeholder-single" >
                                    <option value="0">Sélectionner.....</option>
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
                                <select id="customer_id_search" name="customer_id_search" class="form-control js-example-placeholder-single" >
                                    <option value="0">Tous</option>
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
                            <input class="form-control" name="nom_benef_search" id="nom_benef_search">
                        </div>
                        <div class="col-md-4">
                            <label>Prestataire </label>
                            <select class="form-control" name="pres_id_search" id="pres_id_search">
                                <option value=""></option>

                                @foreach(App\Prestataire::get() as $p)

                                 <option value="{{$p->id}}">{{$p->name}}</option>  

                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <!--<div class="form-group ">
                                <label >Filtre par date </label>
                                <div id="reportrange" class="form-control btn default" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100% ;">
                                    <i class="fa fa-calendar"></i>
                                    <span> </span>
                                    <b class="fa fa-angle-down"></b>
                                </div>
                                <input type="hidden" name="date_debut" id="date_debut" value="" />
                                <input type="hidden" name="date_fin" id="date_fin" value="" />
                            </div>-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button classj="btn btn-circle blue" id="rechercher" type="submit" style="margin-top: 25px;">Rechercher</button>
                        </div>
                    </div>
                </form>
            </div>

          
        </div>

         <?php if (isset($datasearch)) { ?>
           <div></div>

            <table class="table table-striped" id="mytable" style="width:100%">
            <thead >
            <tr id="headtable">
                <th style="width:20%">Référence</th>
                <th style="width:25%">Client</th>
                <th style="width:20%">Abonné</th>
                <th style="width:20%">Etat</th>
              </tr>
            <tr>
                <th style="width:20%">Référence</th>
                <th style="width:25%">Client</th>
                <th style="width:20%">Abonné</th>
                <th style="width:20%">Etat</th>
            </tr>
            </thead>
            <tbody>

            @foreach($datasearch as $do)
                <tr><?php $statut=$do->current_status;  $affecte=$do->affecte;   ?>
                    <td style="width:20%"><a href="{{action('DossiersController@view', $do->id )}}" >{{$do->reference_medic}}</a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $do->id )}}" >Fiche<i class="fa fa-file-txt"></a></td>
                     <td style="width:25%">
                        <?php $customer_id= $do->customer_id ; echo '<small>'. DossiersController::ClientById($customer_id).'</small>';?>
                    </td>
                    <td style="width:20%"><?php echo '<small>'.$do->subscriber_name .' '.$do->subscriber_lastname .'</small>';?></td>
                    <td style="width:20%"> <?php if($statut=='Cloture'){echo 'Clôturé';} else {
                    if($affecte==0 or ($affecte=='') ){echo '<span style="color:red">Non Affecté !</span>';}else {
                        echo 'En cours <br> Affecté à : '. app('App\Http\Controllers\UsersController')->ChampById('name', $affecte);
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
                <th style="width:20%">Référence</th>
                <th style="width:25%">Client</th>
                <th style="width:20%">Abonné</th>
                <th style="width:20%">Etat</th>
              </tr>
            <tr>
                <th style="width:20%">Référence</th>
                <th style="width:25%">Client</th>
                <th style="width:20%">Abonné</th>
                <th style="width:20%">Etat</th>
            </tr>
            </thead>
            <tbody>

            @foreach($dossiers as $dossier)
                <tr><?php $statut=$dossier['current_status'];  $affecte=$dossier['affecte'];   ?>
                    <td style="width:20%"><a href="{{action('DossiersController@view', $dossier['id'])}}" >{{$dossier->reference_medic}}</a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $dossier['id'])}}" >Fiche<i class="fa fa-file-txt"></a></td>
                     <td style="width:25%">
                        <?php $customer_id= $dossier['customer_id']; echo '<small>'. DossiersController::ClientById($customer_id).'</small>';?>
                    </td>
                    <td style="width:20%"><?php echo '<small>'.$dossier['subscriber_name'] .' '.$dossier['subscriber_lastname'] .'</small>';?></td>
                    <td style="width:20%"> <?php if($statut=='Cloture'){echo 'Clôturé';} else {
                    if($affecte==0 or ($affecte=='') ){echo '<span style="color:red">Non Affecté !</span>';}else {
                        echo 'En cours <br> Affecté à : '. app('App\Http\Controllers\UsersController')->ChampById('name', $affecte);
                    }
                    }
                     ?> </td>

                    </td>



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
    <div class="modal fade" id="createfolder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Créer un nouveau dossier</h5>

                </div>
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
                                <label for="type">Affecté à :</label>
                                <select id="type_affectation" name="type_affectation" class="form-control js-example-placeholder-single" readonly="readonly">
                                    <option  value="Najda">Najda</option>
                                    <option   value="VAT">VAT</option>
                                    <option  value="MEDIC">MEDIC</option>
                                    <option   value="Transport MEDIC">Transport MEDIC</option>
                                    <option   value="Transport VAT">Transport VAT</option>
                                    <option  value="Medic International">Medic International</option>
                                    <option   value="Najda TPA">Najda TPA</option>
                                    <option   value="Transport Najda">Transport Najda</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="affecte">Agent:</label>
                                <select   id="affecte" name="affecte"   class="form-control js-example-placeholder-single">
                                    @foreach($users as $user  )
                                        <option
                                                @if($user->id==$iduser)selected="selected"@endif

                                        value="{{$user->id}}">{{$user->name}}</option>

                                    @endforeach
                                </select>
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
@stop