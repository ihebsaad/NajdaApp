@extends('layouts.adminlayout')

@section('content')
    <h3 style="margin-left:50px">Créer un nouveau utilisateur</h3><br><br>

                    <form class="form-horizontal" method="POST" action="{{ route('users.saving') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname" class="col-md-4 control-label">Nom</label>

                            <div class="col-md-6">
                                <input  autocomplete="off"  id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Prénom</label>

                            <div class="col-md-6">
                                <input  autocomplete="off"  id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required  >

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-4 control-label">Identifiant</label>

                            <div class="col-md-6">

                                <input  autocomplete="off"  id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required  >


                            @if ($errors->has('username'))
                                    <span class="invalid-feedback">
                         <strong>{{ $errors->first('username') }}</strong>
                               </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Mot de passe</label>

                            <div class="col-md-6">
                                <input  autocomplete="off"   id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group " style="margin-bottom:30px">
                            <label for="type" class="col-md-4 control-label">Qualification</label>

                            <div class="col-md-6">
                                <select  name="user_type"  id="type"   >
                                    <option value="user"   >Agent </option>
                                    <option value="autonome"   >Agent autonome</option>
                                    <option  value="superviseur"   >Superviseur</option>
                                    <option  value="financier"    >Financier</option>
                                    <option  value="bureau"    >Bureau d'ordre</option>
                                </select>

                            </div>
                        </div>


                      <!--  <div class="form-group">
                            <label for="Role" class="col-md-4 control-label">Rôle</label>

                            <div class="col-md-6">
                                <select  name="user_type">
                                    <option value="user">Agent Simple</option>
                                    <option  value="disp">Dispatcheur</option>
                                    <option  value="superviseur">Superviseur</option>
                                </select>
                             </div>
                        </div>-->


                        <div class="form-group" style="margin-bottom:30px">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>


@endsection

<style>



</style>