@extends('layouts.mainlayout')

@section('content')
 
 
  <!-- début onglet instructions------------------------------------------------------------------ -->
  
   @if(session()->has('AffectMission'))
    <div class="alert alert-success">
       <center> <h4>{{ session()->get('AffectMission') }}</h4></center>
    </div>
  @endif
    
  
 

@endsection