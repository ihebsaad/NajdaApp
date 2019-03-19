@extends('layouts.mainlayout')

@section('content')
<form method="post" action="{{action('EmailController@send')}}"  enctype="multipart/form-data">
    <div class="form-group">
        {{ csrf_field() }}
        <label for="emetteur">destinataire:</label>
        <input id="emetteur" type="text" class="form-control" name="destinataire"/>
    </div>
    <div class="form-group">
        <label for="sujet">sujet :</label>
        <input id="sujet" type="text" class="form-control" name="sujet"/>
    </div>
    <div class="form-group">
        <label for="contenu">contenu:</label>
        <textarea id="contenu" type="text" class="form-control" name="contenu" ></textarea>
    </div>
    <div class="form-group form-group-default">
        <label>Attachements</label>
        <input id="file" type="file" name="files[]"   multiple>
    </div>

    <button  type="submit"  class="btn btn-primary">Envoyer</button>
 </form>


<script type="text/javascript">
    $(document).ready(function(){
        $('#file').change(function(){
            var fp = $("#file");
            var lg = fp[0].files.length; // get length
            var items = fp[0].files;
            var fileSize = 0;

            if (lg > 0) {
                for (var i = 0; i < lg; i++) {
                    fileSize = fileSize+items[i].size; // get file size
                }
                if(fileSize > 12000000 ) {
                    alert('La taille des fichiers ne doit pas dépasser 12 MB');
                    $('#file').val('');
                }
            }
        });
    });
</script>


@endsection