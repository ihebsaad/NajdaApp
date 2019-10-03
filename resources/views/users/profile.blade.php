@extends('layouts.mainlayout')

@section('content')

    <div class="portlet box grey">
        <div class="modal-header">Profil</div>
    </div>

        <div class="row">
 


                        <div class="col-md-10">
                            <table class="table">

                                <form class="form-horizontal" method="POST"   >
                                    {{ csrf_field() }}
                                    <input type="hidden" id="iduser" value="{{$id}}" ></input>
                                    <tbody>
                                    <tr>
                                        <td class="text-primary">Nom </td>
                                        <td>
                                            <input id="lastname" onchange="changing(this)" type="text" class="form-control" name="name"  value="{{ $user->lastname }}" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary">Prénom </td>
                                        <td>
                                            <input id="name" onchange="changing(this)" type="text" class="form-control" name="name"  value="{{ $user->name }}" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary">Login</td>
                                        <td> <input readonly id="email" autocomplete="off" onchange="changing(this)" type="email" class="form-control" name="email"  id="email" value="{{ $user->email }}" />          </td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary">Email Boite</td>
                                        <td> <input id="boite" autocomplete="off" onchange="changing(this)"  type="email" class="form-control" name="boite" id="boite" value="{{ $user->boite }}" />                  </td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary">Mote de passe boite</td>
                                        <td> <input id="passboite" autocomplete="off" onchange="changing(this)"   type="password" class="form-control" name="passboite"  id="passboite" value="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary">Tel</td>
                                        <td>    <input id="tel" onchange="changing(this);"  type="text" class="form-control" name="tel"  id="tel" value="{{ $user->phone }}" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-primary">Signature FR</td>

                                        <td>  <textarea id="signature" onchange="changing(this);"  type="text" class="form-control" name="signature"  id="signature"    >{{$user->signature}}</textarea>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-primary">Signature En</td>
                                        <td>    <textarea id="signature_en" onchange="changing(this);"  type="text" class="form-control" name="signature_en"  id="signature_en">  {{$user->signature_en}}</textarea>
                                        </td>
                                    </tr>
                                <tr>
                                <td class="text-primary">Rôle</td>
                                    <td>

                                        <span class="label label-success">{{$user->user_type}}</span>
                                    </td>
                                </tr>


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