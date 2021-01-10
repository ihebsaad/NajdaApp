
@extends('layouts.mainlayout')

@section('content')
   

 
  <!-- début onglet instructions------------------------------------------------------------------ -->
  
   @if(session()->has('messagekbsSucc'))
    <div class="alert alert-success">
       <center> <h4>{{ session()->get('messagekbsSucc') }}</h4></center>
    </div>
  @endif

    @if(session()->has('messagekbsFail'))
    <div class="alert alert-danger">
       <center> <h4>{{ session()->get('messagekbsFail') }}</h4></center>
    </div>
  @endif

  <p>Action: {{$Action->titre}} </p>
  <p>Mission: {{$Action->Mission->typeMission->nom_type_Mission}} </p>
    <p><a href="{{action('DossiersController@view', $Action->Mission->dossier->id )}}" >Dossier: {{$Action->Mission->dossier->reference_medic}}</a> &nbsp;-&nbsp; {{$Action->Mission->dossier->subscriber_name}} {{$Action->Mission->dossier->subscriber_lastname}} </p>
@if($Action->user_id != $Action->assistant_id)
 @if (count($Action->Mission->activeActionEC)>0)
      <p>Liste des actions actives pour la Mission en cours:  </p>
        <p> @foreach($Action->Mission->activeActionEC  as $d)

         

          <a  href="{{url('dossier/Mission/TraitementAction/'.$d->Mission->dossier->id.'/'.$d->mission_id.'/'.$d->id)}}">
          {{$d->titre}} </a>

         <br>

         @endforeach

        </p>

@else
 
 <p>Il n'y a pas des actions actives pour le moment </p>
        
@endif
@endif

    

  

 

@endsection










