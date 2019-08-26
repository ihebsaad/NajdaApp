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
                                    <h4 id="kbspaneltitle" class="panel-title"> Agents connectés </h4>

              </div>
        				
		  <div class="panel-body" style="display: block;min-height:700px;padding:15px 15px 15px 15px">
		 <?php
use \App\Http\Controllers\UsersController;
   use \App\Http\Controllers\ClientsController;


              function custom_echo($x, $length)
              {
                  if(strlen($x)<=$length)
                  {
                      echo $x;
                  }
                  else
                  {
                      $y=substr($x,0,$length) . '..';
                      echo $y;
                  }
              }

    $user = auth()->user();
    $name=$user->name;
    $iduser=$user->id;
    $user_type=$user->user_type;

    $seance =  DB::table('seance')
        ->where('id','=', 1 )->first();
    $disp=$seance->dispatcheur ;
    $sup=$seance->superviseur ;
    $supmedic=$seance->superviseurmedic ;
    $suptech=$seance->superviseurtech ;
    $charge=$seance->chargetransport ;
    $disptel=$seance->dispatcheurtel ;
    $veilleur=$seance->veilleur ;

    $debut=$seance->debut ;
    $fin=$seance->fin ;

    $parametres =  DB::table('parametres')
        ->where('id','=', 1 )->first();
    $signature=$parametres->signature ;
    $accuse1=$parametres->accuse1 ;
    $accuse2=$parametres->accuse2 ;
    $dollar=$parametres->dollar ;
    $euro=$parametres->euro ;


      use \App\Dossier;


              function convertToHoursMins($time, $format = '%02d:%02d') {
                  if ($time < 1) {
                      return;
                  }
                  $hours = floor($time / 60);
                  $minutes = ($time % 60);
                  return sprintf($format, $hours, $minutes);
              }


              if( ($user_type=='superviseur')  || ( ($user_type=='admin')) ) {
?>
                        <div class="padding:5px 5px 5px 5px"><br>
                           <!-- <h4>Supervision</h4><br>-->
                            <ul id="tabs" class="nav  nav-tabs"  >
                                <li class=" nav-item ">
                                    <a class="nav-link    " href="{{ route('supervision') }}"  >
                                        <i class="fas fa-lg  fa-users-cog"></i>  Supervision
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('affectation') }}"  >
                                        <i class="fas fa-lg  fa-user-tag"></i>  Affectations
                                    </a>
                                </li>
                                <li class="nav-item active">
                                    <a class="nav-link active" href="{{ route('affectation') }}"  >
                                        <i class="fa fa-lg  fa-envelope"></i>  Flux de réceptions
                                    </a>
                                </li>
                            </ul>
                            <table id="tabusers" style="text-align: center ;background-color:#F8F7F6;padding:5px 5px 5px 5px">
                                <thead style="text-align:center;font-size:13px;"><th>Type</th><th>Sujet</th><th>Dossier</th><th>Attachements</th><th>Agent</th></thead>
                            <?php $c=0;
                            foreach($users as $user)
                                {
									
                                }
                                    ?><br>

                            </table>
                        </div>
    <?php } ?>
	
			</div>
			</div><!--panel 1-->
			
			<div class="panel panel-danger col-md-5" style="padding:0 ; ">
                    <div class="panel-heading">
                        <h4 class="panel-title"> </h4>

                    </div>


                   <div class="panel-body scrollable-panel" style="display: block;">
 

                   </div>
            <!-- /.content -->
        </div>

  </div><!-- /main -->

     @endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>

    // function slect all elements from class tag
    function toggle(className, displayState){
        var elements = document.getElementsByClassName(className);

        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = displayState;
        }
    }

    function toggle2(className, displayState){
        var elements = document.getElementsByClassName(className);

        for (var i = 0; i < elements.length; i++){
            elements[i].style.border = displayState;
        }
    }

    function showuser(elm) {
        var userid = elm.id;
        var user = userid.slice( 5);
        //document.getElement('agent').style.display='none';
        toggle('agent', 'none');
        toggle2('usertr', 'none');

        document.getElementById('user-'+user).style.border='2px solid black';
        document.getElementById('agent-'+user).style.display='block';
    }
        function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('home.parametring') }}",
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

<style>
    #tabusers td, #tabusers th{text-align: center;padding-left:5px;padding-right: 5px;}
    #tabusers th{height:60px;background-color: #4FC1E9;color:white;border-left:1px solid white;}
    #tabusers td{border-left:1px solid white;border-bottom:1px solid white;}
    #tabusers tr{margin-bottom:15px;min-height:40px;}


    </style>