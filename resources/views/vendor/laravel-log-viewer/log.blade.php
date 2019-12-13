<style>#mytable_filter{float:right;}</style>
  <title>Historique des opérations</title>


@extends('layouts.adminlayout')

@section('content')

<div class="container-fluid">

    <div class="row">
    <div class="col-lg-3    ">
        <h3 style="margin-left:30px;margin-bottom:30px"><i class="fa fa-file-alt" aria-hidden="true"></i> Fichiers</h3>

      <div class="list-group div-scroll">
        @foreach($folders as $folder)
          <div class="list-group-item">
            <a href="?f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}">
              <span class="fa fa-folder"></span> {{$folder}}
            </a>
            @if ($current_folder == $folder)
              <div class="list-group folder">
                @foreach($folder_files as $file)
                  <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}&f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}"
                    class="list-group-item @if ($current_file == $file) llv-active @endif">
                    {{$file}}
                  </a>
                @endforeach
              </div>
            @endif
          </div>
        @endforeach
        @foreach($files as $file)
          <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
             class="list-group-item @if ($current_file == $file) llv-active @endif">
            {{$file}}
          </a>
        @endforeach
      </div>
    </div>
    <div class="col-lg-9 ">
        <h3 style="margin-left:30px;margin-bottom:30px"><i class="fa fa-calendar" aria-hidden="true"></i> Historique des opérations</h3>


    @if ($logs === null)
        <div>
          Document > 50 Mo, SVP Télécharger le.
        </div>
      @else
        <table id="mytable" style="width:100%" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
          <thead   >
          <tr>
               <!--<th style="width:15%">Type</th>-->
             <!-- <th>Context</th>-->
              <th style="width:15%">Date</th>
             <th style="width:70%">Détails</th>
          </tr>
          </thead>

          <tbody>

          @foreach($logs as $key => $log)
		  @if ($log['level']!='error')
            <tr data-display="stack{{{$key}}}">
               <!-- <td style="width:15%" class="nowrap text-{{{$log['level_class']}}}">
                  <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                </td>-->
               <!-- <td class="text">{{$log['context']}}</td>-->
              <td style="width:15%" class=""><?php $date=$log['date']; echo $date; //$datef = new DateTime($date); echo  date_format($datef, 'd/m/Y   H:i'); ?></td>
              <td style="width:75%" class="text">

                {{{$log['text']}}}

              </td>
            </tr>
		 @endif

          @endforeach

          </tbody>
        </table>
      @endif
      <div class="p-3">
        @if($current_file)
          <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_folder ? $current_folder . "/" . $current_file : $current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
            <span class="fa fa-download"></span> Télécharger le fichier
          </a>
          -
          <a id="clean-log" href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_folder ? $current_folder . "/" . $current_file : $current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
            <span class="fa fa-sync"></span> Vider le fichier
          </a>

  <!--        <a id="delete-log" href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_folder ? $current_folder . "/" . $current_file : $current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
            <span class="fa fa-trash"></span> Delete file
          </a>-->
          @if(count($files) > 1)
            -
           <!-- <a id="delete-all-log" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
              <span class="fa fa-trash-alt"></span> Delete all files
            </a>-->
          @endif
        @endif
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
                  "order": [[ 0, "desc" ]],

                  orderCellsTop: true,
                  dom : '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
                  responsive:true,
                  buttons: [

                     // 'csv', 'excel', 'pdf', 'print'
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

                }, 800));
              });







          });

      </script>
  @stop

