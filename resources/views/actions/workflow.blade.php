
@extends('layouts.mainlayout')

@section('content')
   
    <div class="card uper">
        <div class="card-header">
           Workflow : {{ $act->titre }}
           <br/>
           <br/>
           <br/>
        </div>
        <div class="card-body">
        
                <div style="background-color: #ABF8F8;padding:15px 15px 15px 15px" >

                    @if(!$sousactions->isEmpty())
                    <form method="post" action="{{url('action/updateworkflow/'.$dossier->id.'/'.$act->id)}}}}">
                      {{ csrf_field() }}
                      <input id="rr" type="hidden"  name="id_action"  value="{{$act->id}}" /> 
                        <?php $i = 0;
                         $len = count($sousactions);?>

                        @foreach ($sousactions as $sactions)
                                 
                    <!--<label for="emetteur">emetteur:</label>-->
                    <div class="row">
                        @if ($sactions->realisee)
                           <div class="col-md-11">
                               <input id="emetteur" type="text" name="emetteur" style="width:100% ;background-color:#00FF80; color:black" value="{{ $sactions->titre}}" readonly="true" />
                           </div>
                        @else
                        <div class="col-md-11">
                        <input id="emetteur" type="text" name="emetteur" style="width:100% ; color:black" value="{{ $sactions->titre}}" readonly="true" /></div>
                        <input  type="hidden"  name="sousaction<?php echo($i+1) ?>"  value="{{$sactions->id}}" /> 
                        <div class="col-md-1"><input  type="checkbox"  name="check<?php echo($i+1) ?>"   /> </div>
                        <br><br>
                       <div class="col-md-5"> <textarea style="background-color: #ffffcc; width: 70%; "name="commenta<?php echo($i+1)?>"> ajouter commentaire !</textarea></div>
                        @endif
                   </div>

                    <?php if ($i!=$len-1) { ?>
                                        <div class="row">


                                      <center> <i style="margin-top:10px;margin-bottom: 0px"class="fa fa-2x fa-arrow-down" > </i> </center>
                     </div>

                    <?php } ?>       
                         <br />
                         <?php $i++ ?>
                        @endforeach
                     <center><button  id="EnrWorkf" type="submit"  class="btn btn-primary ">Enregister</button></center>
                    </form>
                  @endif
                </div><br />
         
            <!--<form method="post" action="">
                <div class="form-group">
                    {{ csrf_field() }}
                    <label for="emetteur">emetteur:</label>
                    <input id="emetteur" type="text" class="form-control" name="emetteur"/>
                </div>
                <div class="form-group">
                    <label for="sujet">sujet :</label>
                    <input id="sujet" type="text" class="form-control" name="sujet"/>
                </div>
                <div class="form-group">
                    <label for="contenu">contenu:</label>
                    <input id="contenu" type="text" class="form-control" name="contenu"/>
                </div>
                <button  type="submit"  class="btn btn-primary">Ajouter</button>
                <button id="add"  class="btn btn-primary">Ajax Add</button>
            </form>-->
        </div>
    </div>
@endsection

<script>

var enregi= 0;

jQuery.on("click", )

</script>




