@extends('layouts.mainlayout')

@section('content')

    <?php

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

 



    

if( ($user_type=='superviseur')  || ( ($user_type=='admin')) ) {
?>
                        <div class="padding:50px 50px 50px 50px"><br>
                            <h4>Supervision</h4><br>
                            <ul style="width:600px;background-color:#F8F7F6;padding:50px 50px 50px 50px">

                            <?php
                            foreach($users as $user)
                                {
                                    $role='(Agent)';
                                    if($user->id==$disp){$role='(Dispatcheur)';}
                                    if($user->id==$disptel){$role='(Dispatcheur Téléphonique)';}
                                    if($user->id==$supmedic){$role='(Superviseur Médical)';}
                                    if($user->id==$suptech){$role='(Superviseur Technique)';}
                                    if($user->id==$veilleur){$role='(Veilleur de nuit)';}
                                    if($user->id==$charge){$role='(Chargé de transport)';}
                                    if($user->type=='admin'){$role='(Administrateur)';}
                                  if($user->isOnline()) { echo  '<li><i class="fa fa-user fa-lg" ></i>   '.$user->name.' '.$user->lastname .' - '. $role.' </li>' ;}
                                }
                                    ?><br>

                            </ul>
                        </div>
    <?php } ?>

                    @endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>

    function hideinfos() {
        $('#tab1').css('display','none');
    }
    function hideinfos2() {
        $('#tab2').css('display','none');
    }
    function hideinfos3() {
        $('#tab3').css('display','none');
    }
    function hideinfos4() {
        $('#tab4').css('display','none');
    }
    function showinfos() {
        $('#tab1').css('display','block');
    }
    function showinfos2() {
        $('#tab2').css('display','block');
    }
    function showinfos3() {
        $('#tab3').css('display','block');
    }
    function showinfos4() {
        $('#tab4').css('display','block');
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