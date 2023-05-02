@extends('layouts.fulllayout')

<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/buttons.bootstrap.css') }}" />
<!--   <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/rowReorder.bootstrap.css') }}" />-->
<link rel="stylesheet" type="text/css" href="{{ asset('resources/assets/datatables/css/scroller.bootstrap.css') }}" />

<?php  use App\Http\Controllers\DossiersController;
Use App\Common;

  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
 

?>

@section('content')
    <style>
        .uper {
            margin-top: 10px;
        }
    </style>
    <div class="uper">
     
            @foreach($envoyes as $envoye)
            <div class="email">
                <div class="fav-box">
                    <a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('EnvoyesController@destroy', $envoye['id'])}}" class="btn btn-sm btn-danger btn-responsive" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom"  data-original-title="Supprimer">
                        <i class="fa fa-lg fa-fw fa-trash-alt"></i>

                    </a>
                </div>
                <div class="media-body pl-3">
                    <div class="subject">
                            <?php if($envoye->type=="email") {?><i class="fa  fa-envelope"></i><?php }?><?php if($envoye->type=="sms") {?><i class="fa fa-lg fa-sms"></i><?php }?><?php if($envoye->type=="whatsapp") {?><i class="fa fa-lg fa-whatsapp"></i><?php }?>
                            <?php if($envoye->type=="tel") {?><i class="fa fa-lg fa-phone-square"></i><?php }?><?php if($envoye->type=="fax") {?><i class="fa fa-lg fa-fax"></i><?php }?><?php if($envoye->type=="rendu") {?><i class="fa fa-lg fa-file-sound-o"></i><?php }?>

                                <?php if( ($envoye->type=="email") ||($envoye->type=="sms") ){ ?><a  href="{{action('EnvoyesController@view', $envoye['id'])}}" >{{$envoye->sujet}}</a><small style="margin-top:10px;">{{$envoye->destinataire}}</small></div><?php } ?>
                    <?php if($envoye->type=="fax") { ?><a  href="{{action('EnvoyesController@view', $envoye['id'])}}" > FAX </a><small style="margin-top:10px;">{{$envoye->destinataire}}</small></div><?php } ?>
                    <div class="stats">
                        <div class="row">
                            <div class="col-sm-8 col-md-8 col-lg-8">
                                <span><i class="fa fa-fw fa-clock-o"></i><?php echo  date('d/m/Y H:i', strtotime($envoye->created_at)) ; ?></span>
                             </div>


                        </div>
                    </div>
                </div>
            </div>
        @endforeach
         

  

    </div>
@endsection

<style>#colmn6 input{display:none;}</style>


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
                "aaSorting": [],
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
