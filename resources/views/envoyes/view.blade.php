@extends('layouts.mainlayout')
<?php // use DB; 

Use App\Adresse;
?>
@section('content')

     <div class="row">

        <div class="col-lg-12 ">
<input type="hidden" id="idenvoye" value="<?php echo $envoye['id']?>" ></input>
         <?php if(isset($envoye['dossier'])){
$param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;

  $dossier=App\Dossier::where("reference_medic",trim($envoye['dossier']))->first();
$dossierid=$dossier['id'];
          
/* $dosss=    DB::table('dossiers')->where('reference_medic','like','%'.trim($envoye['dossier'].'%'))->first();
            $dossierid= $dosss['id'];*/

             ?>   <span style="font-weight:bold;"><a  href="<?php echo $urlapp.'/dossiers/view/'.$dossierid;?>" ><?php  echo   $envoye['dossier'].' - '.    \App\Http\Controllers\DossiersController::FullnameAbnDossierById($dossierid );?> </a></span>
<?php if($envoye['type']!=="tel") { ?>
             <div class="btn-group pull-right">
                 <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                     <i class="fas fa-share"></i> Transférer <i class="fa fa-angle-down"></i>
                 </button>
                 <ul class="dropdown-menu pull-right">
                     <li>
                         <a href="{{route('emails.envoimailenreg',[$dossierid,'type'=> 'client','prest'=> 0,'entreeid'=>0,'envoyeid'=>$envoye['id'] ])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                             Au client </a>
                     </li>
                     <li>
                         <a href="{{route('emails.envoimailenreg',[$dossierid,'type'=> 'prestataire','prest'=> 0 ,'entreeid'=>0,'envoyeid'=>$envoye['id'] ])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                             À l'intervenant </a>
                     </li>
                     <li>
                         <a href="{{route('emails.envoimailenreg',[$dossierid,'type'=> 'assure','prest'=> 0 ,'entreeid'=>0,'envoyeid'=>$envoye['id']  ] )}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                             À l'assuré </a>
                     </li>

                 </ul>
             </div>
<?php } ?>
        <br><?php } ?>
     <?php if($envoye['type']==="tel") { 

     	?>
  <div class="btn-group pull-right">
  	 <?php if($envoye['dossier']===null) { 

     	?>
 <button id="afffolder" class="btn   " style="width:180px;background-color: #c5d6eb;color:#333333;"  data-toggle="modal" data-target="#affectfolder"><b><i class="fas fa-folder"></i>  Dispatcher</b></button>
 </div>

<?php } else { 

     	?>
 <button id="afffolder" class="btn   " style="width:180px;background-color: #c5d6eb;color:#333333;"  data-toggle="modal" data-target="#affectfolder"><b><i class="fas fa-folder"></i>  Re-Dispatcher</b></button>
 </div>

     	<?php }}?>


        <?php $type= $envoye['type'];
            if ($type=='email') { echo ' <H3 style="margin-left:20px;margin-bottom:10px">  <i class="fa fa-lg fa-envelope"></i> Email envoyé</H3>'; }
            if ($type=='sms') { echo ' <H3 style="margin-left:20px;margin-bottom:10px"> <i class="fas fa-lg  fa-sms"></i> SMS envoyé</H3>'; }
            if ($type=='fax') { echo ' <H3 style="margin-left:20px;margin-bottom:10px"> <i class="fa fa-lg fa-fax"></i> FAX envoyé</H3>'; }
            
            if ($type=='tel') { echo ' <H3 style="margin-left:20px;margin-bottom:10px"> <i class="fa fa-lg fa-mobile"></i> Appel émis</H3>'; }

    ?>

                <div class="form-group">
                    {{ csrf_field() }}
                    <label for="destinataire">destinataire:</label>
                    <div class="row">
                        <div class="col-md-10">
                            <?php    if ($type=='tel')  { 
 $adressecomm=Adresse::where("champ",$envoye['destinataire'])->first();


                                ?>

                   {{ $envoye->destinataire." (".$adressecomm['prenom']." ".$adressecomm['nom'].")"  }}
               <?php   } else { ?>
                {{ $envoye->destinataire }}
                 <?php   } ?>
                        </div>
                      <!--  <div class="col-md-2">
                            <i id="emailso" onclick="visibilite('autres')" class="fa fa-lg fa-arrow-circle-down" style="margin-right:10px"></i>

                        </div>-->
                       <?php  if($envoye->type==='tel' ){
                ?>
                    <div class="col-md-10">
                      Date:    
                 
                {{ $envoye->reception }}  <?php echo ", "; ?> Duration:
                   <?php 
function convert($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}

                echo  convert($envoye->duration) ?>
             
          
                        
                      <!--  <div class="col-md-2">
                            <i id="emailso" onclick="visibilite('autres')" class="fa fa-lg fa-arrow-circle-down" style="margin-right:10px"></i>

                        </div>-->
                  
                    
                    
                     
                          
                 
             
                      <!--  <div class="col-md-2">
                            <i id="emailso" onclick="visibilite('autres')" class="fa fa-lg fa-arrow-circle-down" style="margin-right:10px"></i>

                        </div>-->
                    
                </div>
                    <?php   } ?>

                    </div>
                </div>

           
               
                   
                
                   
                
                <?php if ($type=='email') {?>
                <div class="form-group" style="margin-top:10px;">
                    <div id="autres" class="row"   >
                     <?php if($envoye->cc !='') {?>
                        <div class="col-md-1">
                            <label for="cc">CC:</label>
                        </div>
                        <div class="col-md-5">
                             {{ $envoye->cc }}
                        </div>
                         <?php } ?>
                         <?php if($envoye->cci !='') {?>

                         <div class="col-md-1">
                            <label for="cci">CCI:</label>
                        </div>
                        <div class="col-md-5">
                            {{ $envoye->cci }}
                        </div>
                         <?php } ?>

                    </div>
                </div>

                <div class="form-group">
                    <label for="sujet">Sujet :</label>
                    <input id="sujet" type="text" class="form-control" name="sujet" required value="{{ $envoye->sujet }}"/>
                </div>
                <?php }?>


                <?php if ( $envoye->type != 'fax' ) {?>
				<div class="form-group">
                    <label for="description">Description :</label>
                    <input onchange="changing(this)" id="description" type="text" class="form-control" name="description" required value="{{ $envoye->description }}" />
                </div>
                <div class="form-group ">
                    <label for="contenu">contenu:</label>
                <div class="form-control" style="overflow:scroll;min-height:200px">
                  <?php $contenu= $envoye['contenu'];
                  echo $contenu;?>

                </div>

                </div>

                <?php } ?>
               <?php if ( $envoye->type == 'tel' ) {?>
                  <div class="form-group ">
                    <label for="media">Média:</label>
                <div>

                  <audio style="width:200px;"controls>
  <source src="<?php  echo  $envoye["path"] ; ?>" type="audio/wav">
 Your browser does not support the audio element.
</audio>

                
                
                
                 </div>

                </div>
                <br><br><br>

<?php } ?>
                <?php if ( $envoye->type == 'fax' ) {?>
				
				 <div class="form-group">
                    <label for="description">Objet :</label>
                    <input id="description" type="text" class="form-control" name="description" required value="{{ $envoye->description }}" />
                </div>
				
				<div class="form-group">
                    <label for="description">Description :</label>
                    <input onchange="changing(this)" id="commentaire" type="text" class="form-control" name="commentaire"   value="{{ $envoye->commentaire }}" />
                </div>
                <?php } ?>

                <button class="btn btn-success " id="genererpdf"> Générer le PDF de l'email </button>

            <?php use App\Attachement ;?>


                     <?php // if ($envoye['nb_attach']>0){
                    echo '<br>Attachements :<br>';

                 //   $attachs = Attachement::where('parent',  $envoye['id'] )->where('boite', '=', 1 )->get();

                $envid=$envoye['id'];
                    $attachs = Attachement::where(function ($query)  use ($envid) {
                        $query->where('envoye_id',$envid )
                      ->where('boite',  1 );
                     })->orWhere(function ($query) use ($envid )   {
                        $query->where('parent', $envid )
                       ->where('boite',  1 );
                     })->get();




                 //  }
                ?>


                @if (!empty($attachs) )
                    <?php $i=1; ?>
                    @foreach ($attachs as $att)
                        <div class="tab-pane fade in" id="pj<?php echo $i; ?>">

                            <h4><b style="font-size: 13px;">{{ $att->nom }}</b> (<a target="_self" style="font-size: 13px;" href="{{ URL::asset('storage'.$att->path) }}" download>Télécharger</a>)</h4>

                        </div>

                            <iframe src="{{ URL::asset('storage'.$att->path) }}" frameborder="0" style="width:100%;min-height:440px;border:1px solid grey"></iframe>

                        @endforeach
                @endif






        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="affectfolder"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            		 <?php if($envoye['dossier']===null) { 

     	?>
                <h3 style="text-align:center" class="modal-title" id="exampleModalLabel">Dispatcher</h3>
<?php } else { ?>
	  <h3 style="text-align:center" class="modal-title" id="exampleModalLabel">Re-Dispatcher</h3>
	<?php } ?>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <form method="post" >
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="type">Dossier :</label>
                         <select id ="affdoss"  class="form-control select2" style="width: 100%">
                             <option></option>
                         <?php foreach($dossiers as $ds)

                               {
                               echo '<option  title="'.$ds->id.'" value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name.' - '.$ds->subscriber_lastname.' </option>';}     ?>
                         </select>
                            <br><br><br>
                        </div>


                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="updatefolder" onclick="document.getElementById('updatefolder').disabled=true" class="btn btn-primary">Dispatcher</button>
            </div>
        </div>
    </div>
</div>


    <script type="text/javascript">

    $("#affdoss").select2();


        function visibilite(divId)
        {
            //divPrecedent.style.display='none';
            divPrecedent=document.getElementById(divId);
            if(divPrecedent.style.display==='none')
            {divPrecedent.style.display='block';	 }
            else
            {divPrecedent.style.display='none';     }
        }

        $(document).ready(function(){



            $('#file').change(function(){
                var fp = $("#file");
                var lg = fp[0].files.length; // get length
                var items = fp[0].files;
                var fileSize = 0;

                if (lg > 0) {
                    for (var i = 0; i < lg; i++) {
                        fileSize = fileSize+items[i].size; // get file size
                    }
                    if(fileSize > 12000000 ) {
                        alert('La taille des fichiers ne doit pas dépasser 12 MB');
                        $('#file').val('');
                    }
                }
            });

            $('#genererpdf').click(function() {


                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('emails.createpdf') }}",
                    method: "POST",
                    data: {   envoye:<?php echo $envoye['id']; ?>, _token: _token},
                    success: function (data) {

                        location.reload();

                    }
                });


            });


        });
function changing(elm) {
        var champ = elm.id;

        var val = document.getElementById(champ).value;

        var envoye = $('#idenvoye').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('envoyes.updating') }}",
            method: "POST",
            data: {envoye: envoye, champ: champ, val: val, _token: _token},
            success: function (data) {
                $('#' + champ).animate({
                    opacity: '0.3',
                });
                $('#' + champ).animate({
                    opacity: '1',
                });

            }
        });
        // } else {

        // }
    }
     $('#updatefolder').click(function(){
            var envoye = $('#idenvoye').val();
            var dossier = $('#affdoss').val();
             var iddossier = $('#affdoss').find("option:selected").attr("title");


            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('envoyes.dispatchf2') }}",
                method:"POST",
                data:{envoye:envoye,dossier:dossier,iddossier:iddossier, _token:_token},
                success:function(data){

                    window.location =data;



                }
            });

        });

    </script>


@endsection
