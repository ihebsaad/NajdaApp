@extends('layouts.adminlayout')

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

    ?>

                    <h3 class="card-title">Tableau de bord</h3><br>
                         <!-- Tabs -->
                        <ul class="nav  nav-tabs">
                            <?php if($user_type=='admin') { ?>

                            <li class="nav-item active">
                                <a class="nav-link  <?php if ($user_type=='admin'){echo 'active';}?> " href="#tab1" data-toggle="tab" onclick="showinfos();hideinfos2();hideinfos3();;hideinfos4()">
                                    <i class="fa-lg fas fa-user-clock"></i>  Séance
                                </a>
                            </li>
                       <!--     <li class="nav-item">
                                <a class="nav-link" href="#tab2" data-toggle="tab" onclick="showinfos2();hideinfos();hideinfos3();hideinfos4()">
                                    <i class="fa-lg fas fa-sliders-h"></i>  Paramètres
                                </a>
                            </li>--->
                            <?php }

                            if( ($user_type=='superviseur')  || ( ($user_type=='admin')) ) {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($user_type=='superviseur'){echo 'active';}?>" href="#tab3" data-toggle="tab" onclick="showinfos3();hideinfos();hideinfos2();hideinfos4()">
                                    <i class="fa-lg fas fa-users"></i>  Supervision
                                </a>
                            </li>
                         <?php  } if( ($user_type=='financier')  || ( ($user_type=='admin')) ) {

                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($user_type=='financier'){echo 'active';}?>" href="#tab4" data-toggle="tab" onclick="showinfos4();hideinfos();hideinfos2();hideinfos3()">
                                    <i class="fa-lg fas fa-users"></i>  Finances
                                </a>
                            </li>
                            <?php }?>
                        </ul>

<?php if($user_type=='admin') { ?>
                <div id="tab1" class="tab-pane fade <?php if ($user_type=='admin'){echo 'active in';}?> ">
                   <div class="padding:50px 50px 50px 50px">
                       <br>
                   <h4>Séance  </h4><br>
                            <?php

                       // ChampById
                          ?>
                            <table class="table">

                                <form class="form-horizontal" method="POST"></form>

                                {{ csrf_field() }}
                                <tbody>
                                <tr>
                                    <td class="text-primary">Début de la séance du jour</td>
                                    <td>
                                            <input id="debut" onchange="changingseance(this)" type="text" class="form-control" name="debut" value="<?php echo $debut; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Fin de la séance du jour</td>
                                    <td>
                                        <input id="fin" onchange="changingseance(this)" type="text" class="form-control" name="fin" value="<?php echo $fin; ?>">
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-primary">Superviseur Médical</td>
                                    <td>
                                        <select   id="superviseurmedic" name="superviseurmedic"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                        @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$supmedic)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>                                       </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Superviseur Technique</td>
                                    <td>
                                        <select   id="superviseurtech" name="superviseurtech"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                        @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$suptech)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>                                       </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Dispatcheur</td>
                                    <td>
                                        <select   id="dispatcheur" name="dispatcheur"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                            @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$disp)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Dispatcheur Téléphonique</td>
                                    <td>
                                        <select   id="dispatcheurtel" name="dispatcheurtel"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                            @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$disptel)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Chargé de transport</td>
                                    <td>
                                        <select   id="charge" name="charge"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                            @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$charge)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            <?php     $date_actu =date("H:i");
                            if ( $date_actu < $debut || ($date_actu > $fin) )
                                {  ?>
                                <tr>
                                    <td class="text-primary">Veilleur de nuit</td>
                                    <td>
                                        <select   id="veilleur" name="veilleur"   class="form-control js-example-placeholder-single">
                                            <option    ></option>
                                        @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$veilleur)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>

                                            @endforeach
                                        </select>                                       </td>
                                </tr>
                              <?php  } ?>
                                </tbody>
                            </table>

                        </div>
                     </div>

               <!--     <div id="tab2" class="tab-pane fade " style="display:block">
                        <div class="padding:50px 50px 50px 50px"><br>
                        <h4>Paramètres</h4><br>

                            <table class="table">

                                <form class="form-horizontal" method="POST"></form>

                                {{ csrf_field() }}
                                <tbody>

                                       <tr>
                                    <td class="text-primary">Signature</td>
                                    <td>
                                        <textarea  class="form-control" onchange="changing(this)" id="signature" style="width:520px;height:300px"><?php echo $signature; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Accusé Fr</td>
                                    <td>
                                        <textarea  class="form-control" onchange="changing(this)" id="accuse1" style="width:520px;height:350px"><?php echo $accuse1 ; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Accusé Eng</td>
                                    <td>
                                        <textarea  class="form-control" onchange="changing(this)" id="accuse2" style="width:520px;height:350px"><?php echo $accuse2; ?></textarea>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>--->
<?php }

if( ($user_type=='superviseur')  || ( ($user_type=='admin')) ) {
?>
                    <div id="tab3" class="tab-pane fade <?php if ($user_type=='superviseur'){echo 'in active';}?>" style="display:block">
                        <div class="padding:50px 50px 50px 50px"><br>
                            <h4>Supervision</h4><br>
                            <ul style="width:600px;background-color:#F8F7F6;padding:50px 50px 50px 50px">

                            <?php
                            foreach($users as $user)
                                {
                                    $role='(Agent)';
                                    if($user->id==$veilleur){$role='(Veilleur de nuit)';}
                                    if($user->id==$disp){$role='(Dispatcheur)';}
                                    if($user->id==$disptel){$role='(Dispatcheur Téléphonique)';}
                                    if($user->id==$supmedic){$role='(Superviseur Médical)';}
                                    if($user->id==$suptech){$role='(Superviseur Technique)';}
                                    if($user->id==$charge){$role='(Chargé de transport)';}
                                    if($user->type=='admin'){$role='(Administrateur)';}
                                  if($user->isOnline()) { echo  '<li><i class="fa fa-user fa-lg" ></i>   '.$user->name.' '.$user->lastname .' - '. $role.' </li>' ;}
                                }
                                    ?><br>

                            </ul>
                        </div>
                    </div>
    <?php }
    if( ($user_type=='financier')  || ( ($user_type=='admin')) ) {

    ?>
                    <div id="tab4" class="tab-pane fade <?php if ($user_type=='financier'){echo 'in active';}?> " style="display:block">
                        <div class="padding:20px 20px 20px 20px"><br>
                            <h4>Paramètres finances</h4><br>

                            <table class="table">

                                <form class="form-horizontal" method="POST"></form>

                                {{ csrf_field() }}
                                <tbody>
                                <tr>
                                    <td class="text-primary">1 Euro en dinars</td>
                                    <td>
                                        <input class="form-control" onchange="changing(this)" type="number" step="0.01" id="euro"  style="width:100px" value="<?php echo $euro; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">1 Dollar Américain en dinars</td>
                                    <td>
                                        <input  class="form-control" onchange="changing(this)" type="number" step="0.01"  id="dollar"  style="width:100px" value="<?php echo $dollar; ?>" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
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