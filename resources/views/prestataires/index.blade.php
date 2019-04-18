@extends('layouts.mainlayout')

@section('content')
    <style>
        .uper {
            margin-top: 40px;
        }
    </style>
    <div class="uper">
        <table class="table table-striped">
            <thead>
            <tr>
                <td>ID</td>
                <td>Nom</td>
                <td>Spécialité</td>
                <td>Type</td>
              </tr>
            </thead>
            <tbody>
            @foreach($prestataires as $prestataire)
                <tr>
                    <td>{{$prestataire->id}}</td>
                     <td><a href="{{action('PrestatairesController@view', $prestataire['id'])}}" >{{$prestataire->nom}}</a></td>
                    <td>{{$prestataire->specialite}}</td>
                    <td>{{$prestataire->typepres}}</td>
 
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection