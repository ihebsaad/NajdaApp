@extends('layouts.mainlayout')

@section('content')
    <style>
        .uper {
            margin-top: 40px;
        }
    </style>
    <div class="uper">
        @if(session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div><br />
        @endif
        <table class="table table-striped">
            <thead>
            <tr>
                <td>ID</td>
                <td>Emetteur</td>
                <td>Date</td>
                <td>Sujet</td>
                 <td>Attachements</td>
             </tr>
            </thead>
            <tbody>
            @foreach($entrees as $entree)
                <tr>
                    <td>{{$entree->id}}</td>
                    <td>{{$entree->emetteur}}</td>
                    <td>{{$entree->reception}}</td>
                     <td><a href="{{action('EntreesController@view', $entree['id'])}}" >{{$entree->sujet}}</a></td>

                    <td>{{$entree->nb_attach}}</td>


                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection