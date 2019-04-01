@extends('layouts.mainlayout')

@section('content')
    <link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />

    @if (!empty( Session::get('success') ))
        <div class="alert alert-success">

        {{ Session::get('success') }}
        </div>

    @endif
<form method="post" action="{{action('EmailController@send')}}"  enctype="multipart/form-data">
    <div class="form-group">
        {{ csrf_field() }}
        <label for="emetteur">destinataire:</label>
        <input id="emetteur" type="email" class="form-control" name="destinataire" required />
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-1">
                <label for="cc">CC :</label>
            </div>
            <div class="col-md-4">
                <input id="cc" type="text" class="form-control" name="cc"  />
            </div>
         <div class="col-md-1">
            <label for="cci">CCI :</label>
        </div>
        <div class="col-md-4">
            <input id="cci" type="text" class="form-control" name="cci"  />
        </div>
    </div>
    </div>

    <div class="form-group">
        <label for="sujet">sujet :</label>
        <input id="sujet" type="text" class="form-control" name="sujet" required/>
    </div>
    <div class="form-group ">
        <label for="contenu">contenu:</label>
       <div class="editor" >
        <textarea id="summernote" style="min-height: 280px;" id="contenu" type="text"  class="textarea tex-com" placeholder="Contenu de l'email ici" name="contenu" required ></textarea>
       </div>
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
