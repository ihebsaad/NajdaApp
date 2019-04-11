@extends('layouts.mainlayout')

@section('content')


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
                            &nbsp;&nbsp;
                            <div align="center">
                                <button type="button" class="btn btn-success btn-sm">Action</button>
                                <button type="button" class="btn btn-primary btn-sm">Message</button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table">

                                <form class="form-horizontal" method="POST"  action="{{action('UsersController@update', $id)}}" >
                                    {{ csrf_field() }}

                                    <tbody>
                                <tr>
                                    <td class="text-primary">Nom complet</td>
                                    <td><p class="user_name_max">     <input id="nom" type="text" class="form-control" name="name"  value={{ $user->name }} />
                                        </p></td>
                                </tr>
                                <tr>
                                    <td class="text-primary">Email</td>
                                    <td> <input id="type" type="text" class="form-control" name="email"  value={{ $user->email }} />                                    </td>
                                </tr>
                                @if($user->phone)
                                    <tr>
                                        <td class="text-primary">Tel</td>
                                        <td>    <input id="type" type="text" class="form-control" name="email"  value={{ $user->phone }} />
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
                                     <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Enregistrer
                                        </button>
                                    </div>
                                    </td>
                                </tr>
                                 </tbody>
                                </form>
                            </table>
                        </div>
 
        </div>
		
		
		
		





	
@endsection