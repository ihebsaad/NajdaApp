
@extends('layouts.mainlayout')

@section('content')
    <style>
        .uper {
            margin-top: 40px;
        }
    </style>
    <div class="card uper">
        <div class="card-header">
            Ajouter
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br />
            @endif
            <form method="post" action="{{ route('entrees.store') }}">
                <div class="form-group">
                    @csrf
                    {{ csrf_field() }}
                    <label for="emetteur">emetteur:</label>
                    <input id="emetteur" type="text" class="form-control" name="emetteur"/>
                </div>
                <div class="form-group">
                    <label for="sujet">sujet :</label>
                    <input id="sujet" type="text" class="form-control" name="sujet"/>
                </div>
                <div class="form-group">
                    <label for="contenu">contenu:</label>
                    <input id="contenu" type="text" class="form-control" name="contenu"/>
                </div>
                <button  type="submit"  class="btn btn-primary">Ajouter</button>
                <button id="add"  class="btn btn-primary">Ajax Add</button>
            </form>
        </div>
    </div>
@endsection


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>
    $(document).ready(function(){

        $('#add').click(function(){
            var emetteur = $('#emetteur').val();
            var sujet = $('#sujet').val();
            var contenu = $('#contenu').val();
            if ((emetteur != '')&&(sujet != '')&&(contenu != ''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('entrees.saving') }}",
                    method:"POST",
                    data:{emetterur:emetterur,sujet:sujet,contenu:contenu, _token:_token},
                    success:function(data){
                        alert('Added successfully');

                    }
                });
            }else{
            alert('ERROR');
            }
        });






    });
</script>
