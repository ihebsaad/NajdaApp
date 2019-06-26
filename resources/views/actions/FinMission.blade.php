
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


    

  

 

@endsection










