@extends('layouts.adminlayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />


<link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />


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
                <div class="col-lg-3"><h2>Emails Clients</h2></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="submit" id="buttModif" class="  btn btn-success  btn-lg"> Envoyer </button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th  class="no-sort" style="width:5%"><label class="check "><b>Tous </b><input type="checkbox" onclick='selectall()' id="casetout" name="casetout" value="">   <span class="checkmark"></span></label></th>
                <th style="width:15%">Nom</th>
                <th style="width:10%">Groupe</th>
                <th style="width:10%">Pays</th>
                <th style="width:10%">Nature</th>
                <th style="width:10%">Statut</th>
                <th style="width:10%"><small>Lang 1</small></th>
                <th style="width:10%"><small>Lang 2</small></th>
              </tr>
            <tr>
                <th></th>
                <th style="width:15%">Nom</th>
                <th style="width:10%">Groupe</th>
                <th style="width:10%">Pays</th>
                <th style="width:10%">Nature</th>
                <th style="width:10%">Statut</th>
                <th style="width:10%">Lang 1</th>
                <th style="width:10%">Lang 2</th>
             </tr>
            </thead>
            <tbody>
            <?php   $listen=array(0=>'',-1=>'All',1=>'Assistance / Assurance',2=>'Avionneur',3=>'Pétrolier / apparenté',4=>'Clinique',5=>'Agence de voyage / Hôtel',6=>'Autre');
            ?>
            @foreach($clients as $client)

                <tr>
                    <td style="width:5%"><label class="check " style="cursor:pointer">  <input   class="checkbox" type="checkbox" id="cl-<?php echo $client->id;   ?>" name="casedossier" value=" "> <span class="checkmark"></span> <?php echo $client['name']?> </label></td>
                    <td style="width:15%"><a href="{{action('ClientsController@view', $client['id'])}}" >{{$client->name}}</a></td>
                    <td style="width:10%"><?php $groupeid= $client['groupe']; echo ClientsController::GroupeById($groupeid);?> </td>
                    <td style="width:10%"><small>{{$client->pays2}}</small></td>
                    <td style="width:10%">
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
                    <td style="width:10%"><?php if ($client->annule ==0){echo 'Actif';}else{echo 'Désactivé';} ?></td>
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
    <div class="modal fade" id="create"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <select class="form-control" id="pays2" class="form-control"    >
                                    <option></option>
                                    @foreach($countries as $pays  )
                                        <option  value="{{$pays->country_name }}">{{$pays->country_name }}</option>
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
							 <label for="type">Cible:</label>
							<select class="form-control"   name="type"   style=" "  >
							<option value="plateau">Plateau</option>
							<option value="gestion">Gestion</option>
                            </select>

                            <?php  $from='24ops@najda-assistance.com';


                            ?>
                            <input type="hidden"   name="from" id="from" value="<?php echo $from; ?>" />
                            <input type="hidden"   name="destinataire"   value="clients" />

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
                /* Disable initial sort */
                "aaSorting": [] ,
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
                var name = $('#name').val();
                var pays = $('#pays2').val();
                if ((name != '')&&(pays != '') )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('clients.saving') }}",
                        method:"POST",
                        data:{name:name,pays:pays, _token:_token},
                        success:function(data){

                             window.location =data;
                        }
                    });
                }else{
                    // alert('ERROR');
                }
            });

        });




        $('#buttModif').click(function(){
             var _token = $('input[name="_token"]').val();
             var clients =new Array();
            j=0;
            var elements = document.getElementsByClassName('checkbox');
            for (var i = 0; i < elements.length; i++){
                //alert(elements[i].value);
                if(elements[i].checked)
                {

                    clients[j]=getClientId(elements[i]);
                    j++;
                }
            }
            taille= clients.length;

          //  clients=JSON.stringify(clients);

            if(taille>0){
             for ( c = 0; c< taille; c++) {
                addItem(clients[c]);
                }

             $("#sendmails").modal('show');


            }
            else{ alert('sélectinnez des clients ! ');}


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

        function getClientId(elm)
        {
            var idelm=elm.id;
            var dossid=idelm.slice(3);
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
