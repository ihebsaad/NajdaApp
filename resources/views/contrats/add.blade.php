@extends('layouts.adminlayout')

@section('content')
    <div class="portlet box grey">
        <div class="modal-header">Contrat Client</div>
    </div><div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="nom" id="nom"  value="{{ $contrat->nom }}">
                            </div>
                        </div>
                        <div class="col-md-6">

 						   <div class="form-group">
                                <label for="type">Type :</label>
                                <select class="form-control"  name="type" id="type" onchange="changing(this)"  >
								<option <?php if($contrat->type=='commun'){echo 'selected="selected"';} ?>  value="commun">Commun</option>
								<option <?php if($contrat->type=='particulier'){echo 'selected="selected"';} ?>  value="particulier">Particulier</option>
								</select>

                            </div>
                       </div>


                        <input type="hidden" id="idtp" class="form-control"   value="{{ $contrat->id }}">
             </div>
<h2>Règles </h2>
 <?php
    $type_missions = TypeMission::get(); ?>
			<table style="width:850px"> 
			<tr id="ligne-1"  >
			<td>
			<select onchange="changing(this)"    name="mission1" id="mission1"     class="form control select2"   style="width:650px" >
			    <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission1==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:50px" class="form-control " name="operateur1" id="operateur1"  value="{{ $contrat->operateur1}}">
			<option></option>
			<option  <?php if ($contrat->operateur1=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur1=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option <?php if ($contrat->operateur1=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input" name="val1" id="val1"  value="{{ $contrat->val1 }}">
			</td>
			
			<td><img onclick="show('ligne-2')" src="{{ asset('public/img/add.png') }}"></img></td><td><img  onclick="hide('ligne-1')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>
			
		<tr id="ligne-2"  >
			<td>
			<select onchange="changing(this)"    name="mission2" id="mission2"     class="form control select2"   style="width:650px" >
			    <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission2==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:50px" class="form-control " name="operateur2" id="operateur2"  value="{{ $contrat->operateur2}}">
			<option></option>
			<option  <?php if ($contrat->operateur2=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur2=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option <?php if ($contrat->operateur2=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input" name="val2" id="val2"  value="{{ $contrat->val2 }}">
			</td>
			
			<td><img onclick="show('ligne-3')" src="{{ asset('public/img/add.png') }}"></img></td><td><img  onclick="hide('ligne-2')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>
			
			
		<tr id="ligne-3"  >
			<td>
			<select onchange="changing(this)"    name="mission3" id="mission3"     class="form control select2"   style="width:650px" >
			    <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission3==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:50px" class="form-control " name="operateur3" id="operateur3"  value="{{ $contrat->operateur3}}">
			<option></option>
			<option  <?php if ($contrat->operateur3=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur3=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option <?php if ($contrat->operateur3=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input" name="val3" id="val3"  value="{{ $contrat->val3 }}">
			</td>
			
			<td><img onclick="show('ligne-4')" src="{{ asset('public/img/add.png') }}"></img></td><td><img  onclick="hide('ligne-3')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>
			
			
			</table>
			
 
			 
    </form>
                </div>

  </div>

@endsection


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    function show(divId)
    {  
 	div =document.getElementById(divId);
     div.style.display='table-row';	  
    }
	  function hide(divId)
    {  
 	div=document.getElementById(divId);
      div.style.display='none';     
    }
	
    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var contrat = $('#idtp').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('contrats.updating') }}",
            method: "POST",
            data: {contrat: contrat , champ:champ ,val:val, _token: _token},
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
