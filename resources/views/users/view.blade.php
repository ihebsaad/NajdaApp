@extends('layouts.mainlayout')

@section('content')
    <div class="form-group">
        {{ csrf_field() }}
        <label for="ID">ID:</label>
        <input id="id" type="text" class="form-control" name="id"  readonly value={{ $user->id }} />
    </div>
    <div class="form-group">
         <label for="name">Nom:</label>
        <input id="name" type="text" class="form-control" name="nom"  value={{ $user->name }} />
    </div>
<div class="form-group">
    <label for="type">Email :</label>
    <input id="type" type="text" class="form-control" name="email"  value={{ $user->email }} />
</div>
<div class="form-group">
    <label for="user_type">Role :</label>
     <select  name="user_type"   >
        <option value="user"  <?php if($user->user_type=='user') {echo'selected';}?> >Simple</option>
        <option  value="admin"  <?php if($user->user_type=='admin') {echo'selected';}?>  >Admin</option>
    </select>
</div> 



	
@endsection