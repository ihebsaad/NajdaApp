@extends('layouts.adminlayout')

@section('content')
    <div class="portlet box grey">
        <div class="modal-header">Contrat Client</div>
    </div><div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="nom" id="nom"  value="{{ $contrat->nom }}">
                            </div>
                        </div>
                        <div class="col-md-4">

 						   <div class="form-group">
                                <label for="type">Type :</label>
                                <select class="form-control"  name="type" id="type" onchange="changing(this)"  >
								<option <?php if($contrat->type=='commun'){echo 'selected="selected"';} ?>  value="commun">Commun</option>
								<option <?php if($contrat->type=='particulier'){echo 'selected="selected"';} ?>  value="particulier">Particulier</option>
								</select>

                            </div>
                       </div>
					   
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Type de dossier</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="type_dossier" id="type_dossier"  value="{{ $contrat->type_dossier }}">
                            </div>
                        </div>

                        <input type="hidden" id="idtp" class="form-control"   value="{{ $contrat->id }}">
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
        <option  <?php if ($contrat->mission1==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="operateur1" id="operateur1"  value="{{ $contrat->operateur1}}">
			<option></option>
			<option  <?php if ($contrat->operateur1=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur1=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($contrat->operateur1=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($contrat->operateur1=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($contrat->operateur1=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val1" id="val1"  value="{{ $contrat->val1 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison1" id="liaison1"   >
			<option></option>
 			<option  <?php if ($contrat->liaison1=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($contrat->liaison1=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>
			<td><img style="width:30px" onclick="show('ligne-2')" src="{{ asset('public/img/add.png') }}"></img></td><td></img></td>
			</tr>
			
		<tr id="ligne-2"  <?php if ($contrat->mission2==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission2" id="mission2"     class="form control select2"   style="width:650px" >
			 <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission2==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="operateur2" id="operateur2"  value="{{ $contrat->operateur2}}">
			<option></option>
			<option  <?php if ($contrat->operateur2=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur2=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($contrat->operateur2=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($contrat->operateur2=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($contrat->operateur2=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val2" id="val2"  value="{{ $contrat->val2 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison2" id="liaison2"   >
			<option></option>
 			<option  <?php if ($contrat->liaison2=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($contrat->liaison2=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-3')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-2')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>
			
			
		<tr id="ligne-3"  <?php if ($contrat->mission3==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission3" id="mission3"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission3==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur3" id="operateur3"  value="{{ $contrat->operateur3}}">
			<option></option>
			<option  <?php if ($contrat->operateur3=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur3=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($contrat->operateur3=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($contrat->operateur3=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($contrat->operateur3=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val3" id="val3"  value="{{ $contrat->val3 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison3" id="liaison3"   >
			<option></option>
 			<option  <?php if ($contrat->liaison3=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($contrat->liaison3=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-4')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-3')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>
	
		<tr id="ligne-4"  <?php if ($contrat->mission4==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission4" id="mission4"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission4==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur4" id="operateur4"  value="{{ $contrat->operateur4}}">
			<option></option>
			<option  <?php if ($contrat->operateur4=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur4=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($contrat->operateur4=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($contrat->operateur4=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($contrat->operateur4=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val4" id="val4"  value="{{ $contrat->val4 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison4" id="liaison4"   >
			<option></option>
 			<option  <?php if ($contrat->liaison4=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($contrat->liaison4=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-5')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-4')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>
			
			
			

		<tr id="ligne-5"  <?php if ($contrat->mission5==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission5" id="mission5"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission5==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur5" id="operateur5"  value="{{ $contrat->operateur5}}">
			<option></option>
			<option  <?php if ($contrat->operateur5=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur5=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($contrat->operateur5=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($contrat->operateur5=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($contrat->operateur5=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>

			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val5" id="val5"  value="{{ $contrat->val5 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison5" id="liaison5"   >
			<option></option>
 			<option  <?php if ($contrat->liaison5=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($contrat->liaison5=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-6')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-5')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>

			
			
			
			
		<tr id="ligne-6"  <?php if ($contrat->mission6==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission6" id="mission6"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission6==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur6" id="operateur6"  value="{{ $contrat->operateur6}}">
			<option></option>
			<option  <?php if ($contrat->operateur6=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur6=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($contrat->operateur6=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($contrat->operateur6=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($contrat->operateur6=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>

			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val6" id="val6"  value="{{ $contrat->val6 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison6" id="liaison6"   >
			<option></option>
 			<option  <?php if ($contrat->liaison6=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($contrat->liaison6=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-7')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-6')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>

			
			
			
		<tr id="ligne-7"  <?php if ($contrat->mission7==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission7" id="mission7"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission7==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur7" id="operateur7"  value="{{ $contrat->operateur7}}">
			<option></option>
			<option  <?php if ($contrat->operateur7=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur7=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($contrat->operateur7=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($contrat->operateur7=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($contrat->operateur7=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>

			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val7" id="val7"  value="{{ $contrat->val7 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison7" id="liaison7"   >
			<option></option>
 			<option  <?php if ($contrat->liaison7=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($contrat->liaison7=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-8')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-7')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>

			
			
		<tr id="ligne-8"  <?php if ($contrat->mission8==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission8" id="mission8"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission8==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur8" id="operateur8"  value="{{ $contrat->operateur8}}">
			<option></option>
			<option  <?php if ($contrat->operateur8=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur8=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($contrat->operateur8=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($contrat->operateur8=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($contrat->operateur8=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>

			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val8" id="val8"  value="{{ $contrat->val8 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison8" id="liaison8"   >
			<option></option>
 			<option  <?php if ($contrat->liaison8=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($contrat->liaison8=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-9')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-8')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>

			
			
		<tr id="ligne-9"  <?php if ($contrat->mission9==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission9" id="mission9"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission9==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur9" id="operateur9"  value="{{ $contrat->operateur9}}">
			<option></option>
			<option  <?php if ($contrat->operateur9=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur9=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($contrat->operateur9=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($contrat->operateur9=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($contrat->operateur9=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>

			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val9" id="val9"  value="{{ $contrat->val9 }}">
			</td>
			<td>
			<select onchange="changing(this)"  style="width:90px;font-size:20px;font-weight:bold;height:40px" class="form-control " name="liaison9" id="liaison9"   >
			<option></option>
 			<option  <?php if ($contrat->liaison9=='&&'){echo 'selected="selected"';}?> value="&&">  ET  </option>
			<option <?php if ($contrat->liaison9=='||'){echo 'selected="selected"';}?>  value="||" >  OU  </option>
			</select>
			</td>			
			<td><img style="width:30px"  onclick="show('ligne-10')" src="{{ asset('public/img/add.png') }}"></img></td><td><img style="width:30px"  onclick="hide('ligne-9')" src="{{ asset('public/img/remove.png') }}"></img></td>
			</tr>

		<tr id="ligne-10"  <?php if ($contrat->mission10==0){ ?>style="display:none" <?php } ?>  >
			<td>
			<select onchange="changing(this)"    name="mission10" id="mission10"     class="form control select2"   style="width:650px" >
			  <option></option>
   <?php
        foreach($type_missions as $tm)
        { ?>
        <option  <?php if ($contrat->mission10==$tm->id){echo 'selected="selected"';}?>   value="<?php echo $tm->id; ?>"><?php  echo $tm->nom_type_Mission; ?> </option>

        <?php  }
        ?>
			</select>
			</td>
			<td>
			<select onchange="changing(this)"  style="width:70px;font-size:20px;font-weight:bold;;height:40px " class="form-control " name="operateur10" id="operateur10"  value="{{ $contrat->operateur10}}">
			<option></option>
			<option  <?php if ($contrat->operateur10=='='){echo 'selected="selected"';}?> value="=">  =  </option>
			<option  <?php if ($contrat->operateur10=='>'){echo 'selected="selected"';}?> value=">">  >  </option>
			<option  <?php if ($contrat->operateur10=='>='){echo 'selected="selected"';}?> value=">=">  >=  </option>
			<option <?php if ($contrat->operateur10=='<'){echo 'selected="selected"';}?>  value="<" >  <  </option>
			<option <?php if ($contrat->operateur10=='<='){echo 'selected="selected"';}?>  value="<=" >  <=  </option>
			</select>
			</td>
			<td>
			<input onchange="changing(this)" style="width:100px" type="number" class="form-control input number" name="val10" id="val10"  value="{{ $contrat->val10 }}">
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
