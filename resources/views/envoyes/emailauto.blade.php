@extends('layouts.adminlayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />


<link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />


@section('content')
    <?php use \App\Http\Controllers\DossiersController;     ?>
    <style>
        .uper {
            margin-top: 10px;
        }
        table td {font-size:13px;}
    </style>
    <div class="uper">
        <div class="portlet box grey">
            <div class="row">
                <div class="col-lg-3"><h2>Emails automatiques envoyés</h2></div>
                <div class="col-md-6">
                   
                </div>
            </div>
        </div>
        
        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
             
                <th style="width:15%">type email</th>
                <th style="width:10%">emetteur</th>
                <th style="width:10%">destinataire</th>
                <th style="width:10%">cc</th>
                <th style="width:10%">sujet</th>
                <th style="width:10%">contenu</th>
                <th style="width:10%">Date d'envoi</th>
                <th style="width:10%">Dossier</th>
                <th style="width:10%">Facture</th>
                <th style="width:10%">Client</th>
                <th style="width:10%">Prestataire</th
              </tr>
            <tr>
               
               <th style="width:15%">type email</th>
                <th style="width:10%">emetteur</th>
                <th style="width:10%">destinataire</th>
                <th style="width:10%">cc</th>
                <th style="width:10%">sujet</th>
                <th style="width:10%">contenu</th>
                <th style="width:10%">Date d'envoi</th>
                <th style="width:10%">Dossier</th>
                <th style="width:10%">Facture</th>
                <th style="width:10%">Client</th>
                <th style="width:10%">Prestataire</th>
             </tr>
            </thead>
            <tbody>
            
            @foreach($emailsauto as $ema)

                <tr>
                    <td style="width:15%"><a href="#" >{{$ema->type}}</a></td>
                    <td style="width:10%">{{$ema->emetteur}} </td>
                    <td style="width:10%">{{$ema->destinataire}}</td>
                    <td style="width:10%">{{$ema->cc}} </td>
                    <td style="width:10%">{{$ema->sujet}}</td>
                    <td>
  <a href="#" class="btn btn-link too-long" title="Contenu de l'email envoyé" 
  data-content='{{$ema->contenutxt}}' 
  data-placement="bottom">Cliquez pour voir le contenu <br>
  (et recliquez pour le cacher)
  </a>
</td>
                   
                    <td style="width:10%">{{$ema->created_at}}</td>
                    <td style="width:10%"><a href="{{action('DossiersController@view', $ema->dossierid )}}" >{{$ema->dossier}}</a></td>
                    <td style="width:10%">{{$ema->facture_ref}}</td>
                    <td style="width:10%">{{$ema->client}}</td>
                    <td style="width:10%">{{$ema->prestataire}}</td>

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
            $('.too-long').popover();


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