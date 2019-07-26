@extends('layouts.adminlayout')

@section('content')
    <style>
        .uper {
            margin-top: 40px;
        }
    </style>
         <div class="row">
            <div class="col-md-8"><H2> Liste des utilisateurs</H2></div><div class="col-md-3"><a data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom"  style="float:right;margin-right:20px;margin-bottom:25px;padding:3px 3px 3px 3px;border:1px solid #4fc1e9;" href="{{action('UsersController@create')}}"><span role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Ajouter un utilisateur"  class="fa fa-fw fa-plus fa-2x"></span></a><br></div>
        </div>
            <table class="table table-striped">
            <thead>
            <tr>
                <td>ID</td>
                <td>Nom</td>
                <td>Email</td>
                <td>Rôle</td>
                <td>Status</td>
              </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                     <td><a href="{{action('UsersController@view', $user['id'])}}" >{{$user->name .' '.$user->lastname }}</a></td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->user_type}}</td>
                    <td><?php if ($user->isOnline()){  if($user->statut==0){echo '<span class="label label-success">Connecté</span> ';} else{ echo '<span class="label label-warning">En Pause</span> ';  }    } else{echo '<span class="label label-danger">Hors ligne</span>';}  ?></td>
                </tr>
            @endforeach
            </tbody>
        </table>
 @endsection