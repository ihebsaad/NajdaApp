@extends('layouts.adminlayout')

@section('content')
<?php
  use App\Template_doc ; 
								 
?>
    <div class="portlet box grey">
        <div class="modal-header"><h1>Rubrique</h1></div>
    </div>
	<div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">
  
                    <div class="row">					
                        <div class="col-md-4">
                         <label for="inputError" class="control-label">Nom</label>
 		               <input onchange="changing(this)" type="text" class="form-control input" name="nom" id="nom"  value="{{ $rubrique->nom }}">
                       </div>
					 <div class="col-md-4">
                         <label for="inputError" class="control-label">Commentaire</label>
 		               <input onchange="changing(this)" type="text" class="form-control input" name="commentaire" id="commentaire"  value="{{ $rubrique->commentaire }}">
</div>
                      	
 <div class="col-md-4">
 
                         <label for="inputError" class="control-label">Document</label>


 		               <select class=" form-control select2 pec"  name="pec"  id="pec" onchange="changing(this);"  >
                    <option value="Select">Selectionner</option>
                     

                    <option value="7"  <?php if($rubrique->pec=="7")  {echo 'selected="selected"';}?> >PEC_analyses_medicales</option>
<option value="31"  <?php if($rubrique->pec=="31")  {echo 'selected="selected"';}?> >PEC_frais_medicaux</option>
<option value="24"  <?php if($rubrique->pec=="24")  {echo 'selected="selected"';}?> >PEC_pharmacie</option>
<option value="23"  <?php if($rubrique->pec=="23")  {echo 'selected="selected"';}?> >PEC_opticien</option>
<option value="18"  <?php if($rubrique->pec=="18")  {echo 'selected="selected"';}?> >PEC_frais_imagerie</option>
<option value="12"  <?php if($rubrique->pec=="12")  {echo 'selected="selected"';}?> >PEC_consultation</option>
<option value="26"  <?php if($rubrique->pec=="26")  {echo 'selected="selected"';}?> >PEC_Reeducation</option>
<option value="0"  <?php if($rubrique->pec==0) {echo'selected="selected"';}?> >Pas de document</option>

                </select>
                      </div>	
	 
					
                       <input type="hidden" id="id" class="form-control"   value="{{ $rubrique->id }}">
             </div>

    </form>
  </div>

  
	
@endsection


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>



    

       
<script>
 
   
   
   
$(".pec").select2();


           

            

          

    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var rubrique = $('#id').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('rubriques.updating') }}",
            method: "POST",
            data: {rubrique: rubrique , champ:champ ,val:val, _token: _token},
            success: function (data) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });

    }

	
	   
	
       

</script>
