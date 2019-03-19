
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
            <form method="post" action="{{ route('dossiers.store') }}">
                <div class="form-group">
                     {{ csrf_field() }}
                    <label for="ref">Ref:</label>
                    <input id="ref" type="text" class="form-control" name="ref"/>
                </div>
                <div class="form-group">
                    <label for="type">type :</label>
                    <input id="type" type="text" class="form-control" name="type"/>
                </div>
                <div class="form-group">
                    <label for="affecte">affecte:</label>
                    <input id="affecte" type="text" class="form-control" name="affecte"/>
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
            var ref = $('#ref').val();
            var type = $('#type').val();
            var affecte = $('#affecte').val();
            if ((emetteur != '')&&(sujet != '')&&(contenu != ''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.saving') }}",
                    method:"POST",
                    data:{ref:ref,type:type,affecte:affecte, _token:_token},
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
