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
                <td>Ref</td>
                <td>type</td>
                <td>Agent</td>
              </tr>
            </thead>
            <tbody>
            @foreach($dossiers as $dossier)
                <tr>
                    <td>{{$dossier->id}}</td>
                     <td><a href="{{action('DossiersController@view', $dossier['id'])}}" >{{$dossier->reference_medic}}</a></td>
                    <td>{{$dossier->type_dossier}}</td>
                    <td>{{$dossier->affecte}}</td>

 

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection