@extends('layouts.mainlayout')

@section('content')
    <form class="form-horizontal" method="POST"  action="{{action('UsersController@update', $id)}}" >
        {{ csrf_field() }}


		
    <div class="form-group">
         <label for="name">Nom:</label>
        <input id="name" type="text" class="form-control" name="name"  value={{ $user->name }} />
    </div>
<div class="form-group">
    <label for="type">Email :</label>
    <input id="type" type="text" class="form-control" name="email"  value={{ $user->email }} />
</div>
<div class="form-group">
    <label for="user_type">Rôle :</label>
	<span>	{{$user->user_type}}</span>
</div>

 
    </form>



	
@endsection