@extends('layouts.supervislayout')

@section('content')

         <div id="mainc" class=" row" style="padding:30px 30px 30px 30px  ">
         @if ($errors->any())
             <div class="alert alert-danger">
                 <ul>
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div><br />
         @endif

    @if (!empty( Session::get('success') ))
        <div class="alert alert-success">
        {{ Session::get('success') }}
        </div>

    @endif
 
            <div class="panel panel-primary column col-md-6"  style="margin-left:30px;margin-right:50px;padding:0" >
              <div class="panel-heading">
                                    <h4 id="kbspaneltitle" class="panel-title">  </h4>
              
              </div>
        				
		  <div class="panel-body" style="display: block;min-height:450px;padding:15px 15px 15px 15px">
		 <?php
use \App\Http\Controllers\UsersController;
    $user = auth()->user();
 $dossiers = Dossier::all();
 ?>
			</div>
			</div><!--panel 1-->
			
			<div class="panel panel-danger col-md-5" style="padding:0 ; ">
                    <div class="panel-heading">
                        <h4 class="panel-title"> <br></h4>
         
                    </div>


                   <div class="panel-body scrollable-panel" style="display: block;">
                 
                  </div>
 
            </div><!--panel 2-->
			
			
            <!-- /.content -->
        </div>

  </div><!-- /main -->
	<style>
 
        </style>
     @endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>

     

    function changingseance(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('home.parametring2') }}",
            method: "POST",
            data: {  champ:champ ,val:val, _token: _token},
            success: function ( ) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });

    }

</script>

