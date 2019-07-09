@extends('layouts.fulllayout')

@section('content')


                    <h3 class="card-title">Tableau de bord</h3><br>
                         <!-- Tabs -->
                        <ul class="nav  nav-tabs">

                            <li class="nav-item active">
                                <a class="nav-link  active " href="#tab1" data-toggle="tab" onclick="showinfos();hideinfos2();hideinfos3();">
                                    <i class="fa-lg fas fa-user-clock"></i>  Séance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tab2" data-toggle="tab" onclick="showinfos2();hideinfos();hideinfos3()">
                                    <i class="fa-lg fas fa-sliders-h"></i>  Paramètres
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tab3" data-toggle="tab" onclick="showinfos3();hideinfos();hideinfos2()">
                                    <i class="fa-lg fas fa-users"></i>  Utilisateurs Connectés
                                </a>
                            </li>
                        </ul>


                <div id="tab1" class="tab-pane fade active in">
                   <div class="padding:50px 50px 50px 50px">
                       <br>
                   <h4>Séance</h4><br>
                            <?php
                            $seance =  DB::table('seance')
                                ->where('id','=', 1 )->first();
                            $disp=$seance->dispatcheur ;
                            $sup=$seance->superviseur ;
                            $debut=$seance->debut ;
                            $fin=$seance->fin ;

                       $parametres =  DB::table('parametres')
                           ->where('id','=', 1 )->first();
                       $signature=$parametres->signature ;
                       $accuse1=$parametres->accuse1 ;
                       $accuse2=$parametres->accuse2 ;
                       $dollar=$parametres->dollar ;
                       $euro=$parametres->euro ;


                       // ChampById
                          ?>
                            <table class="table">

                                <form class="form-horizontal" method="POST"></form>

                                {{ csrf_field() }}
                                <tbody>
                                <tr>
                                    <td class="text-primary">Début</td>
                                    <td>
                                            <input id="debut" onchange="changing(this)" type="text" class="form-control" name="debut" value="<?php echo $debut; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Fin</td>
                                    <td>
                                        <input id="fin" onchange="changing(this)" type="text" class="form-control" name="fin" value="<?php echo $fin; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Dispatcheur</td>
                                    <td>
                                        <select   id="dispatcheur" name="dispatcheur"   class="form-control js-example-placeholder-single">
                                            @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$disp)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Superviseur</td>
                                    <td>
                                        <select   id="superviseur" name="superviseur"   class="form-control js-example-placeholder-single">
                                            @foreach($users as $user  )
                                                <option
                                                        @if($user->id==$sup)selected="selected"@endif

                                                value="{{$user->id}}">{{$user->name}}</option>

                                            @endforeach
                                        </select>                                       </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                     </div>
                    <div id="tab2" class="tab-pane fade " style="display:block">
                        <div class="padding:50px 50px 50px 50px"><br>
                        <h4>Paramètres</h4><br>

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
                    </div>

                    <div id="tab3" class="tab-pane fade " style="display:block">
                        <div class="padding:50px 50px 50px 50px"><br>
                            <h4>Utilisateurs Connectés</h4><br>
                            <ul style="width:600px;background-color:#F8F7F6;padding:50px 50px 50px 50px">

                            <?php
                            foreach($users as $user)
                                {
                                    $role='(Agent)';
                                    if($user->id==$disp){$role='(Dispatcheur)';}
                                    if($user->id==$sup){$role='(Superviseur)';}
                                    if($user->type=='admin'){$role='(Administrateur)';}
                                  if($user->isOnline()) { echo  '<li><i class="fa fa-user fa-lg" ></i>   '.$user->name.' '.$user->lastname .' - '. $role.' </li>' ;}
                                }
                                    ?><br>

                            </ul>
                        </div>
                    </div>

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
    function showinfos() {
        $('#tab1').css('display','block');
    }
    function showinfos2() {
        $('#tab2').css('display','block');
    }
    function showinfos3() {
        $('#tab3').css('display','block');
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

</script>