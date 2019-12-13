@extends('layouts.adminlayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />


@section('content')
    <style>
        .uper {
            margin-top: 40px;
        }
    </style>
         <div class="row">
            <div class="col-md-8"><H2> Liste des utilisateurs</H2></div><div class="col-md-3"><a data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom"  style="float:right;margin-right:20px;margin-bottom:25px;padding:3px 3px 3px 3px;border:1px solid #4fc1e9;" href="{{action('UsersController@create')}}"><span role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Ajouter un utilisateur"  class="fa fa-fw fa-plus fa-2x"></span></a><br></div>
        </div>
    <table class="table table-striped" id="mytable" style="width:100%">
        <thead>
        <tr id="headtable">
            <th>ID</th>
            <th>Nom</th>
            <th>Qualification</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Qualification</th>
                <th>Statut</th>
                <th> </th>
              </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr><?php $qual=$user->user_type; if($qual=='user'){$qual='agent';}?>
                    <td>{{$user->id}}</td>
                     <td><a href="{{action('UsersController@view', $user['id'])}}" >{{$user->name .' '.$user->lastname }}</a></td>
                    <td><?php echo $qual;?></td>
                    <td><?php if ($user->isOnline()){  if($user->statut==0){echo '<span class="label label-success">Connecté</span> ';} else{ echo '<span class="label label-warning">En Pause</span> ';  }    } else{echo '<span class="label label-danger">Hors ligne</span>';}  ?></td>
                  <td>  @can('isAdmin')
                        <a  onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('UsersController@destroy', $user['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                            <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                        </a>
                      @endcan</td>
                </tr>
            @endforeach
            </tbody>
        </table>
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

            // Restore state
       /*     var state = table.state.loaded();
            if ( state ) {
                table.columns().eq( 0 ).each( function ( colIdx ) {
                    var colSearch = state.columns[colIdx].search;

                    if ( colSearch.search ) {
                        $( '#mytable thead tr:eq(1) th:eq(' + index + ') input', table.column( colIdx ).footer() ).val( colSearch.search );

                    }
                } );

                table.draw();
            }

*/

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
// Apply the search
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



/*
            $('#add').click(function(){
                var name = $('#name').val();
                if ((name != '')  )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('typeprestations.saving') }}",
                        method:"POST",
                        data:{name:name, _token:_token},
                        success:function(data){

                            //   alert('Added successfully');
                            window.location =data;


                        }
                    });
                }else{
                    // alert('ERROR');
                }
            });
*/
        });

    </script>
@stop