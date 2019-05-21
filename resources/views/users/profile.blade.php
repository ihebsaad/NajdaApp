@extends('layouts.mainlayout')

@section('content')

    <div class="portlet box grey">
        <div class="modal-header">Profil</div>
    </div>

        <div class="row">
 
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="text-center mbl">
                                 </div>
                            </div>
                            <div align="center">
                                <h3 class="user_name_max">{!! $user->name !!} </h3>
                                <p>{!! $user->email !!}</p>
								
                            </div>


                        </div>
                        <div class="col-md-8">
                            <table class="table">

                                <form class="form-horizontal" method="POST"   >
                                    {{ csrf_field() }}
                                    <input type="hidden" id="iduser" value="{{$id}}" ></input>
                                    <tbody>
                                <tr>
                                    <td class="text-primary">Nom complet</td>
                                    <td><p class="user_name_max">
                                            <input id="nom" onchange="changing(this)" type="text" class="form-control" name="name"  value={{ $user->name }} />
                                        </p></td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Email</td>
                                    <td> <input id="email" autocomplete="off" onchange="changing(this)" type="email" class="form-control" name="email"  id="email" value={{ $user->email }} />          </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Email Boite</td>
                                    <td> <input id="boite" autocomplete="off" onchange="changing(this)"  type="email" class="form-control" name="boite" id="boite" value={{ $user->boite }} />                  </td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Mote de passe boite</td>
                                    <td> <input id="passboite" autocomplete="off" onchange="changing(this)"  type="password" class="form-control" name="passboite"  id="passboite" value={{ $user->passboite }} />                                    </td>
                                </tr>
                                @if($user->phone)
                                    <tr>
                                        <td class="text-primary">Tel</td>
                                        <td>    <input id="tel" onchange="changing(this);"  type="text" class="form-control" name="tel"  id="tel" value={{ $user->phone }} />
                                        </td>
                                    </tr>
                                @endif
                                @if($user->address)
                                    <tr>
                                        <td class="text-primary">Adresse</td>
                                        <td>{!! $user->address !!} </td>
                                    </tr>
                                @endif
                               <tr>
                                <td class="text-primary">Rôle</td>
                                    <td>

                                        <span class="label label-success">{{$user->user_type}}</span>
                                    </td>
                                </tr>
                              <!--
                                <tr>
                                    <td class="text-primary">Skype</td>
                                    <td>{!!$user->skype!!}</td>
                                </tr>-->
                                <tr>
                                    <td colspan="2">


                                    </td>
                                </tr>
                                 </tbody>
                                </form>
                            </table>
                        </div>
 
        </div>




 <!--   <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->



    <script>



            function changing(elm) {
                var champ = elm.id;

                var val = document.getElementById(champ).value;

                var user = $('#iduser').val();
                //if ( (val != '')) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('users.updating') }}",
                    method: "POST",
                    data: {user: user, champ: champ, val: val, _token: _token},
                    success: function (data) {
                        $('#' + champ).animate({
                            opacity: '0.3',
                        });
                        $('#' + champ).animate({
                            opacity: '1',
                        });

                    }
                });
                // } else {

                // }
            }

    </script>


	
@endsection