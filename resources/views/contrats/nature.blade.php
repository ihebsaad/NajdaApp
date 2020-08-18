@extends('layouts.adminlayout')

@section('content')
    <div class="portlet box grey">
        <div class="modal-header">Nature du Contrat Client</div>
    </div><div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">

                    <div class="row">
				    <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="nom" id="nom"  value="{{ $nature->nom }}">
                            </div>
                        </div>
						
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Contrat</label>
								<?php $contrat=\App\Contrat::where('id',$nature->contrat)->first();?>
                                <input readonly type="text" class="form-control input"  value="<?php echo $contrat->nom; ?>" />
                            </div>
                        </div>
  
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Type de dossier</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="type_dossier" id="type_dossier"  value="{{ $nature->type_dossier }}">
                            </div>
                        </div>

                        <input type="hidden" id="idn" class="form-control"   value="{{ $nature->id }}">
             </div>
  <style>
	  img{cursor:pointer;}td{padding-left:10px;padding-right:10px;}
	  select{height:40px;margin-bottom:10px;}
	  input [type="number"],.number{width:100px;height:40px;font-weight:bold;font-size:18px;margin-top:-5px;}
  </style>
<h2>Règles </h2>
 <?php
    $type_missions = \App\TypeMission::get(); ?>
			<table style="width:850px"> 
			<tr id="ligne-1"  >
			<td>
			<select onchange="changing(this)"    name="mission1" id="mission1"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($nature->mission1==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="operateur1" id="operateur1"  value="{{ $nature->operateur1}}">
			<option></option>
			<option  <?php if ($nature->operateur1=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($nature->operateur1=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($nature->operateur1=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($nature->operateur1=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($nature->operateur1=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val1" id="val1"  value="{{ $nature->val1 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison1" id="liaison1"   >
			<option></option>
 			<option  <?php if ($nature->liaison1=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($nature->liaison1=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>
			<td><img style="width:30px" onclick="show('ligne-2')" src="{{ asset('public/img/add.png') }}"></img></td><td></img></td>
			</tr>
			
		<tr id="ligne-2"  <?php if ($nature->mission2==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission2" id="mission2"     class="form control select2"   style="width:650px" >
			 <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($nature->mission2==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="operateur2" id="operateur2"  value="{{ $nature->operateur2}}">
			<option></option>
			<option  <?php if ($nature->operateur2=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($nature->operateur2=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($nature->operateur2=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($nature->operateur2=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($nature->operateur2=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val2" id="val2"  value="{{ $nature->val2 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison2" id="liaison2"   >
			<option></option>
 			<option  <?php if ($nature->liaison2=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($nature->liaison2=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-3')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-2')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>
			
			
		<tr id="ligne-3"  <?php if ($nature->mission3==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission3" id="mission3"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($nature->mission3==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur3" id="operateur3"  value="{{ $nature->operateur3}}">
			<option></option>
			<option  <?php if ($nature->operateur3=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($nature->operateur3=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($nature->operateur3=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($nature->operateur3=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($nature->operateur3=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val3" id="val3"  value="{{ $nature->val3 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison3" id="liaison3"   >
			<option></option>
 			<option  <?php if ($nature->liaison3=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($nature->liaison3=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-4')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-3')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>
	
		<tr id="ligne-4"  <?php if ($nature->mission4==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission4" id="mission4"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($nature->mission4==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur4" id="operateur4"  value="{{ $nature->operateur4}}">
			<option></option>
			<option  <?php if ($nature->operateur4=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($nature->operateur4=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($nature->operateur4=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($nature->operateur4=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($nature->operateur4=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val4" id="val4"  value="{{ $nature->val4 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison4" id="liaison4"   >
			<option></option>
 			<option  <?php if ($nature->liaison4=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($nature->liaison4=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-5')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-4')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>
			
			
			

		<tr id="ligne-5"  <?php if ($nature->mission5==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission5" id="mission5"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($nature->mission5==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur5" id="operateur5"  value="{{ $nature->operateur5}}">
			<option></option>
			<option  <?php if ($nature->operateur5=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($nature->operateur5=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($nature->operateur5=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($nature->operateur5=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($nature->operateur5=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>

			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val5" id="val5"  value="{{ $nature->val5 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison5" id="liaison5"   >
			<option></option>
 			<option  <?php if ($nature->liaison5=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($nature->liaison5=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-6')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-5')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>

			
			
			
			
		<tr id="ligne-6"  <?php if ($nature->mission6==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission6" id="mission6"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($nature->mission6==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur6" id="operateur6"  value="{{ $nature->operateur6}}">
			<option></option>
			<option  <?php if ($nature->operateur6=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($nature->operateur6=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($nature->operateur6=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($nature->operateur6=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($nature->operateur6=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>

			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val6" id="val6"  value="{{ $nature->val6 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison6" id="liaison6"   >
			<option></option>
 			<option  <?php if ($nature->liaison6=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($nature->liaison6=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-7')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-6')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>

			
			
			
		<tr id="ligne-7"  <?php if ($nature->mission7==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission7" id="mission7"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($nature->mission7==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur7" id="operateur7"  value="{{ $nature->operateur7}}">
			<option></option>
			<option  <?php if ($nature->operateur7=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($nature->operateur7=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($nature->operateur7=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($nature->operateur7=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($nature->operateur7=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>

			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val7" id="val7"  value="{{ $nature->val7 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison7" id="liaison7"   >
			<option></option>
 			<option  <?php if ($nature->liaison7=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($nature->liaison7=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-8')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-7')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>

			
			
		<tr id="ligne-8"  <?php if ($nature->mission8==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission8" id="mission8"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($nature->mission8==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur8" id="operateur8"  value="{{ $nature->operateur8}}">
			<option></option>
			<option  <?php if ($nature->operateur8=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($nature->operateur8=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($nature->operateur8=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($nature->operateur8=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($nature->operateur8=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>

			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val8" id="val8"  value="{{ $nature->val8 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison8" id="liaison8"   >
			<option></option>
 			<option  <?php if ($nature->liaison8=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($nature->liaison8=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-9')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-8')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>

			
			
		<tr id="ligne-9"  <?php if ($nature->mission9==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission9" id="mission9"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($nature->mission9==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur9" id="operateur9"  value="{{ $nature->operateur9}}">
			<option></option>
			<option  <?php if ($nature->operateur9=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($nature->operateur9=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($nature->operateur9=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($nature->operateur9=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($nature->operateur9=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>

			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val9" id="val9"  value="{{ $nature->val9 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison9" id="liaison9"   >
			<option></option>
 			<option  <?php if ($nature->liaison9=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($nature->liaison9=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-10')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-9')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>

		<tr id="ligne-10"  <?php if ($nature->mission10==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission10" id="mission10"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($nature->mission10==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur10" id="operateur10"  value="{{ $nature->operateur10}}">
			<option></option>
			<option  <?php if ($nature->operateur10=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($nature->operateur10=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($nature->operateur10=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($nature->operateur10=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($nature->operateur10=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val10" id="val10"  value="{{ $nature->val10 }}">
			</td>
			
			<td></td><td><img style="width:30px"  onclick="hide('ligne-10')" src="{{ asset('public/img/remove.png') }}"></img></td>
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
        var nature = $('#idn').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('contrats.changing') }}",
            method: "POST",
            data: {nature: nature , champ:champ ,val:val, _token: _token},
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
