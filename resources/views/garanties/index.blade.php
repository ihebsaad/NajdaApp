@extends('layouts.adminlayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

@section('content')
<?php
 $assures=\App\Dossier::where('ID_assure','<>','')->pluck('ID_assure')->toArray();;
 $assures=array_unique($assures);
 $assures=array_values($assures);
	 
 $assuresG=\App\Garantie::pluck('ID_assure')->toArray();
   
								 
?>
    <style>
        .uper {
            margin-top: 10px;
        }
        .no-sort input{display:none;}
    </style>
    <div class="uper">
         <div class="portlet box grey">
            <div class="row">
                <div class="col-lg-6"><h2>Tables de garanties</h2></div>
                <div class="col-lg-6">
                    <button id="addgr" class="btn btn-md btn-success"   data-toggle="modal" data-target="#create"><b><i class="fas fa-plus"></i> Ajouter une garantie</b></button>
                </div>
            </div>
        </div>
        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th style="width:10%">ID</th>
 
                 <th style="width:10%">Actions</th>
              </tr>
            <tr>
                <th style="width:10%">ID</th>

              <th class="no-sort" style="width:10%">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($garanties as $garantie)
                <?php
          	 	$dossier=\App\Dossier::where('ID_assure',trim($garantie->id_assure))->first();


                ?>

                <tr>
                    <td  ><a href="{{action('GarantiesController@view', $garantie['id'])}}" ><?php echo sprintf("%04d",$garantie->id);?></a></td>
                     <td  > </td>
					<td><?php echo $garantie->nom ; ?></td>
					<td><?php echo $garantie->description ; ?></td>
                      <td    >
                          @can('isAdmin')
                              <a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('GarantiesController@destroy', $garantie['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                  <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                              </a>
                          @endcan
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
    <!-- Modal -->
    <div class="modal fade" id="create"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter une Garantie </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <form method="post" >
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="type">Assuré :</label>
                                <select class="form-control select2"  required id="id_assure" style="width:100%" >
								<option></option>
								<?php
								 foreach($assures as $assure)
								{
									$doss=\App\Dossier::where('ID_assure',trim($assure))->first();
									if (! in_array(trim($assure),$assuresG))
									{echo '<option value="'.$assure.'"> '.$assure.' (  '. $doss->subscriber_name.' '.$doss->subscriber_lastname.') </option>';}
								}
								 
								?>
								</select>

                            </div>
							
						   <div class="form-group">
                                <label for="type">Val1 :</label>
                                 <input class="form-control" type="number" id="val2" />

                            </div>
						   <div class="form-group">
                                <label for="type">Val2 :</label>
                                 <input class="form-control" type="number" id="val3" />

                            </div>
						   <div class="form-group">
                                <label for="type">Val3 :</label>
                                 <input class="form-control" type="number" id="val4" />

                            </div>
							<div class="form-group">
                                <label for="type">Val4 :</label>
                                 <input class="form-control" type="number" id="val1" />

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
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {
    $("#id_assure").select2();


            $('#mytable thead tr:eq(1) th').each( function () {
                var title = $('#mytable thead tr:eq(0) th').eq( $(this).index() ).text();
                $(this).html( '<input class="searchfield" type="text"   />' );
            } );

            var table = $('#mytable').DataTable({
                /* Disable initial sort */
                "aaSorting": [] ,
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
                var id_assure = $('#id_assure').val();
                var val1 = $('#val1').val();
                var val2 = $('#val2').val();
                var val3 = $('#val3').val();
                var val4 = $('#val4').val();
                if ((id_assure != '')  )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('garanties.saving') }}",
                        method:"POST",
                        data:{id_assure:id_assure,val1:val1,val2:val2,val3:val3,val4:val4, _token:_token},
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