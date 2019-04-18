@extends('layouts.mainlayout')

@section('content')
 
<div class="form-group">
     {{ csrf_field() }}
    <label for="sujet">type :</label>
    <input id="sujet" type="text" class="form-control" name="sujet"  value={{ $note->type }} />
</div>
<div class="form-group">
    <label for="contenu">contenu:</label>
    <div class="form-control" style="min-height:200px">
	<textarea class="form-control"   name="contenu">
    <?php $contenu= $note['contenu'];
	echo $contenu;
	?>
	</textarea>
    </div>
 
 </div>
 
@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>

    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var dossier = $('#iddossupdate').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('notes.updating') }}",
            method: "POST",
            data: {dossier: dossier , champ:champ ,val:val, _token: _token},
            success: function (data) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });
        // } else {

        // }
    }

    function disabling(elm) {
        var champ=elm;

        var val =0;
        //  var type = $('#type').val();
        var dossier = $('#iddossupdate').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('notes.updating') }}",
            method: "POST",
            data: {dossier: dossier , champ:champ ,val:val, _token: _token},
            success: function (data) {

            }
        });
        // } else {

        // }
    }

</script>
