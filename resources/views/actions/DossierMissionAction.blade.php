
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
  <p>Mission: {{$Action->Mission->titre}} </p>
    <p>Dossier: {{$Action->Mission->dossier->reference_medic}} </p>

 @if ($Action->Mission->activeActionEC)
      <p>Liste des actions actives pour la Mission en cours:  </p>
        <p> @foreach($Action->Mission->activeActionEC  as $d)

         

          <a  href="{{url('dossier/Mission/TraitementAction/'.$d->Mission->dossier->id.'/'.$d->mission_id.'/'.$d->id)}}">
          {{$d->titre}} </a>

         <br>

         @endforeach

        </p>

@else
 
 <p>cette mission est terminée </p>
        
@endif
    

  

 

@endsection










