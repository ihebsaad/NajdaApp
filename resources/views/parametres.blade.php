@extends('layouts.mainlayout')

@section('content')




                    <h3 class="card-title">Paramètres</h3>
                         <!-- Tabs -->
                        <ul class="nav  nav-tabs">

                            <li class="nav-item active">
                                <a class="nav-link  active show" href="#tab1" data-toggle="tab">
                                    <i class="fas a-lg fa-exchange-alt"></i>  Séance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tab2" data-toggle="tab">
                                    <i class="fas fa-lg  fa-ambulance"></i>  Paramètres
                                </a>
                            </li>
                        </ul>


                <div id="tab1" class="tab-pane fade active in">
                   <div class="padding:50px 50px 50px 50px">
                   <h4>Séance</h4>
                            <?php
                            $seance =  DB::table('seance')
                                ->where('id','=', 1 )->first();
                            $disp=$seance->dispatcheur ;
                            $sup=$seance->superviseur ;
                            $debut=$seance->debut ;
                            $fin=$seance->fin ;
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
                    <div id="tab2" class="tab-pane fade">
                        <div class="padding:50px 50px 50px 50px">
                        <h4>Paramètres</h4>


                        </div>
                    </div>


@endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>

    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var equipement = $('#id').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('home.parametring') }}",
            method: "POST",
            data: {  champ:champ ,val:val, _token: _token},
            success: function (data) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });
        // } else {

        // }
    }

        </script>