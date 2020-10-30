@extends('layouts.adminlayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

@section('content')
<?php
  use App\Template_doc ; 
								 
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
                <div class="col-lg-6"><h2>Tables de rubriques</h2></div>
                <div class="col-lg-6">
                    <button id="addgr" class="btn btn-md btn-success"   data-toggle="modal" data-target="#create"><b><i class="fas fa-plus"></i> Ajouter une rubrique
                </div>
            </div>
        </div>
        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th style="width:10%">ID</th>
                <th style="width:30%">Nom</th>
                <th style="width:40%">Commentaire</th>
                <th style="width:40%">Document</th>
 
                 <th style="width:10%">Actions</th>
              </tr>
            <tr>
                <th style="width:10%">ID</th>
				<th style="width:30%">Nom</th>
                <th style="width:40%">Commentaire</th>
                <th style="width:40%">Document</th>
              <th class="no-sort" style="width:10%">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rubriques as $rubrique)
                <?php
 
                ?>

                <tr>
                    <td  ><a href="{{action('RubriquesController@view', $rubrique['id'])}}" ><?php echo sprintf("%04d",$rubrique->id);?></a></td>
					<td><?php echo $rubrique->nom ; ?></td>
					<td><?php echo $rubrique->commentaire ; ?></td>
<td><?php 

$rub=Template_doc::where('id',$rubrique->pec)->first();



echo $rub['nom'] ; ?></td>
                      <td    >
                          @can('isAdmin')
                              <a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('RubriquesController@destroy', $rubrique['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
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
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter une rubrique </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <form method="post" >
                            {{ csrf_field() }}


							
							
						   <div class="form-group">
                                <label for="nom">Nom :</label>
                                 <input class="form-control" type="text" id="nom" />

                            </div>
						   <div class="form-group">
                                <label for="commentaire">Commentaire :</label>
                                 <textarea class="form-control"  id="commentaire"  ></textarea>

                            </div> 
		   <div class="form-group">
<div class="row">
                                <label  class="control-label" for="pec">Document :</label>
     </div>                             

                            
<div class="row">
 <select class="form-control select2" style="width: 565px"  required id="pec" name="pec" >
                                        <option value="Select">Selectionner</option>
                                    <?php
                                       /* $usedtemplates = Document::where('dossier',$dossier->id)->distinct()->get(['template']);
                                        $usedtid=array();
                                        foreach ($usedtemplates as $tempu) {
                                            $usedtid[]=$tempu['template'];
                                        }*/
                                        $templatesd = Template_doc::orderBy('nom','asc')->whereIn('id', [7, 31, 24,23,18,12,26])->get();
                                        $docwithcl = array();
                                    ?>
  
                                        @foreach ($templatesd as $tempdoc)
                                        
                                      <option value={{ $tempdoc["id"] }} >{{ $tempdoc["nom"] }}</option>
                                                                                
                                        @endforeach
<option value="0" >Pas de document</option>
                                        
                                   </select>
 </div> 
							
        </div>                  </form>
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
   
$("#pec").select2();


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
                var nom = $('#nom').val();
                var commentaire = $('#commentaire').val();
var pec = $('#pec').val();
                 if ((nom != '')  )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('rubriques.saving') }}",
                        method:"POST",
                        data:{nom:nom,commentaire:commentaire ,pec:pec, _token:_token},
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
