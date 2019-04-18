
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
            <form method="post" action="{{ route('notes.store') }}">
                <div class="form-group">
                     {{ csrf_field() }}
                    <label for="ref">contenu:</label>
                    <input id="ref" type="text" class="form-control" name="contenu"/>
                </div>
                <div class="form-group">
                    <label for="type">type :</label>
                    <input id="type" type="text" class="form-control" name="type"/>
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
            var contenu = $('#contenu').val();
            var type = $('#type').val();
 
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('notes.saving') }}",
                    method:"POST",
                    data:{contenu:contenu,type:type, _token:_token},
                    success:function(data){
                        alert('Added successfully');

                    }
                });
    
        });






    });
</script>
