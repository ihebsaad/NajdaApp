
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
            <form method="post" action="{{ route('prestataires.store') }}">
                <div class="form-group">
                     {{ csrf_field() }}
                    <label for="nom">Name:</label>
                    <input id="nom" type="text" class="form-control" name="nom"/>
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
            var nom = $('#nom').val();
            var typepres = $('#typepres').val();
             if ((nom != '')&&(type != ''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('clients.saving') }}",
                    method:"POST",
                    data:{nom:nom,type:type, _token:_token},
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
