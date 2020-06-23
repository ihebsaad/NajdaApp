@extends('layouts.mainlayout')

@section('content')

    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>


    <script src="{{  URL::asset('public/js/upload_files/vpb_uploader.js') }}" type="text/javascript"></script>


   <!--  <script src="{{  URL::asset('public/js/upload_files/jquery.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/upload_files/bootstrap.js') }}" type="text/javascript"></script>
    <script type="text/javascript" charset="utf-8" language="javascript" src="{{  URL::asset('public/js/upload_files/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" charset="utf-8" language="javascript" src="{{  URL::asset('public/js/upload_files/DT_bootstrap.js') }}"></script> --> 
  
   
        <script type="text/javascript">
$(document).ready(function()
{
    // Call the main function
    new vpb_multiple_file_uploader
    ({
        vpb_form_id: "theform", // Form ID
        autoSubmit: true,
        vpb_server_url: "upload.php" 
    });
});
</script>

    <link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php use \App\Http\Controllers\EnvoyesController;     ?>
            <?php use \App\Http\Controllers\EntreesController;     ?>
            <?php use \App\Http\Controllers\PrestatairesController;     ?>
            <?php use \App\Http\Controllers\ClientsController;

            if($entreeid>0){$entree = \App\Entree::find($entreeid);
            $de =$entree->emetteur ;
             $to  = $entree->destinataire ;
             $date=   date('d/m/Y H:i', strtotime( $entree->reception   )) ;
             $sujet= $entree->sujet;
             $contenu=$entree->contenu;

            }
            else{$envoye = \App\Envoye::find($envoyeid);
                $de =$envoye->emetteur ;
                $to  = $envoye->destinataire ;
                $date=   date('d/m/Y H:i', strtotime( $envoye->reception   )) ;
                $sujet= $envoye->sujet;
                $contenu=$envoye->contenu;
            }

            ?>

            <div class="panel">


                <div class="panel-body pan">

                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">

                            <span class="badge pull-right"></span>
                            <i class="fa fa-inbox fa-fw mrs"></i>
                            Rédiger un email

                        </li>
                        <li >
                            <a   href="{{ route('boite') }}">
                                <span class="badge pull-right"></span>
                                <i class="fa fa-envelope-square fa-fw mrs"></i>
                                Boîte de réception
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('envoyes') }}">
                                <span class="badge pull-right"><?php  echo EnvoyesController::countenvoyes(); ?></span>
                                <i class="fa fa-paper-plane fa-fw mrs"></i>
                                Envoyées
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('envoyes.brouillons') }}">
                                <span class="badge badge-orange pull-right"><?php echo EnvoyesController::countbrouillons(); ?></span>
                                <i class="fa fa-edit fa-fw mrs"></i>
                                Brouillons
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('entrees.archive') }}">
                                <span class="badge badge-orange pull-right"><?php echo EntreesController::countarchives(); ?></span>
                                <i class="fa fa-archive fa-fw mrs"></i>
                                Archive
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        <div class="col-lg-9 ">
            <?php if(isset($dossier)){?>   <h4 style="font-weight:bold;"><a  href="{{action('DossiersController@view',$dossier->id)}}" ><?php echo   $dossier->reference_medic .' - '.    \App\Http\Controllers\DossiersController::FullnameAbnDossierById($dossier->id);?> </a></h4><?php } ?>

            <form  enctype="multipart/form-data" id="theform" method="POST" action="{{action('EmailController@send')}}"    onsubmit="return checkForm(this);"  >
                {{ csrf_field() }}

                <input id="dossier" type="hidden" class="form-control" name="dossier"  value="{{$doss}}" />
                <input id="envoye" type="hidden" class="form-control" name="envoye"  value="" />
                <input id="brsaved" type="hidden" class="form-control" name="brsaved"  value="0" />


                <?php $typea=trim(strtoupper($dossier->type_affectation));
                $from='';
                if($typea=='NAJDA'){$from='24ops@najda-assistance.com';}
                if($typea=='VAT'){$from='hotels.vat@medicmultiservices.com';}
                if($typea=='MEDIC'){$from='assistance@medicmultiservices.com';}
                if($typea=='TRANSPORT MEDIC'){$from='ambulance.transp@medicmultiservices.com';}
                if($typea=='TRANSPORT VAT'){$from='vat.transp@medicmultiservices.com';}
                if($typea=='MEDIC INTERNATIONAL'){$from='operations@medicinternational.tn';}
                if($typea=='NAJDA TPA'){$from='tpa@najda-assistance.com';}
                if($typea=='TRANSPORT NAJDA'){$from='taxi@najda-assistance.com';}
                if($typea=='X-PRESS'){$from='x-press@najda-assistance.com';}

                $user = auth()->user();
                $user_type=$user->user_type;
                if( $user_type =='bureau' ||  $user_type =='financier' )
                {
                    $from='finances@najda-assistance.com';
                }
                ?>
                <input type="hidden"   name="from" id="from" value="<?php echo $from; ?>" />


                <div class="row">
                    <label for="destinataire">Destinataire:</label>
                    <div class="row">
                        <?php if($type=='client')
                        {?>
                        <input type="text" readonly class="form-control" value="<?php  echo ClientsController::ClientChampById('name',$refdem) ;?> " />
                        <?php }
                        ?>
                        <?php if($type=='prestataire')
                        {?>
                        <select class="form-control" id="prest"   >
                            <option ></option>
                            @foreach($prestataires as $prestat)
                                <option  <?php  if($prest==$prestat){ echo 'selected="selected" ';}  ?> value="<?php echo $prestat ;?>"> <?php   echo PrestatairesController::ChampById('name',$prestat); ;?></option>
                            @endforeach
                        </select>
                        <?php }
                        ?>
                    </div>
                    <label for="destinataire">Adresse(s):</label>
                    <div class="row">
                        <div class="col-md-10">
                            <select id="destinataire" required  class="form-control" name="destinataire[]"  multiple >
                                @foreach($listeemails as  $mail)
                                    <option   value="<?php echo $mail ;?>"> <?php echo $mail ;?>  <small style="font-size:11px">(<?php echo PrestatairesController::NomByEmail( $mail);?>) - '<?php echo PrestatairesController::QualiteByEmail($mail);?>' ('<?php echo PrestatairesController::TypeEmail($mail);?>' , '<?php echo PrestatairesController::RemarqueByEmail($mail);?>)' </small> </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <i id="emailso" onclick="visibilite('autres')" class="fa fa-lg fa-arrow-circle-down" style="margin-right:10px"></i> (cc,cci)
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top:10px;">
                    <div id="autres" class="row"  style="display:none " >
                        <div  class="row"  style="margin-bottom:10px" >
                            <div class="col-md-2">
                                <label for="cc">CC:</label>
                            </div>
                            <div class="col-md-10">
                                <select id="cc" style="width:100%"   class="itemName form-control" name="cc[]" multiple   >
                                    <option></option>
                                    <option value="vat@medicmultiservices.com">vat@medicmultiservices.com</option>
                                    <option value="fact.vat-groupe@najda-assistance.com">fact.vat-groupe@najda-assistance.com</option>
                                    <option value="finances@medicmultiservices.com">finances@medicmultiservices.com</option>
                                    <option value="dirops@najda-assistance.com">dirops@najda-assistance.com</option>
                                    <option value="controle1@medicmultiservices.com">controle1@medicmultiservices.com</option>
                                    <option value="smq@medicmultiservices.com">smq@medicmultiservices.com</option>
                                    <option value="chef.plateau@najda-assistance.com">chef.plateau@najda-assistance.com</option>
                                    <option value="mohsalah.harzallah@gmail.com">mohsalah.harzallah@gmail.com</option>
                                    <option value="mahmoud.helali@gmail.com">mahmoud.helali@gmail.com</option>
                                 </select>
                            </div>
                        </div>
                        <div  class="row"  style="margin-bottom:10px" >
                            <div class="col-md-2">
                                <label for="cci">CCI:</label>
                            </div>
                            <div class="col-md-10">
                                <select id="cci"  style="width:100%"   class="itemName form-control " name="cci[]" multiple  >
                                    <option></option>
                                    <option value="vat@medicmultiservices.com">vat@medicmultiservices.com</option>
                                    <option value="voyages.assistance.tunisie@gmail.com">voyages.assistance.tunisie@gmail.com</option>
                                    <option value="fact.vat-groupe@najda-assistance.com">fact.vat-groupe@najda-assistance.com</option>
                                    <option value="finances@medicmultiservices.com">finances@medicmultiservices.com</option>
                                    <option value="dirops@najda-assistance.com">dirops@najda-assistance.com</option>
                                    <option value="controle1@medicmultiservices.com">controle1@medicmultiservices.com</option>
                                    <option value="smq@medicmultiservices.com">smq@medicmultiservices.com</option>
                                    <option value="chef.plateau@najda-assistance.com">chef.plateau@najda-assistance.com</option>
                                    <option value="nejib.karoui@gmail.com">nejib.karoui@gmail.com </option>
                                    <option value="mohsalah.harzallah@gmail.com">mohsalah.harzallah@gmail.com</option>
                                    <option value="mahmoud.helali@gmail.com">mahmoud.helali@gmail.com</option>
                                    <option value="facturation.vat@medicmultiservices.com">facturation.vat@medicmultiservices.com</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>

              <?php  if($typea=='NAJDA TPA'){
                 ?>
                <div class="form-group">
                    <label for="sujet">Sujet :</label>
                    <?php if($type=='prestataire')
                    { ?>
                    <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabnC ?> - N/Réf(O/Ref): <?php echo $ref ?>"/>
                    <?php }
                    if ($type=='assure') {?>
                    <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabnC ?> - N/Réf(O/Ref): <?php echo $ref ?>"/>
                    <?php        } ?>
                    <?php  if  ($type=='client') {
                    if ($langue=='francais'){ ?>
                         <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabnC ?> - V/Réf: <?php echo $refclient ?>   - N/Réf: <?php echo $ref ?>"/>
                 <?php   }else{ ?>
                         <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabnC ?> - Y/Ref: <?php echo $refclient ?>   - O/Ref: <?php echo $ref ?>"/>
                 <?php   }
                        ?>
                     <?php   } ?>
                </div>

            <?php }else{  ?>

                <div class="form-group">
                    <label for="sujet">Sujet :</label>
                    <?php if($type=='prestataire')
                    { ?>
                    <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabn ?> - N/Réf(O/Ref): <?php echo $ref ?>"/>
                    <?php }
                    if ($type=='assure') {?>
                    <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabn ?> - N/Réf(O/Ref): <?php echo $ref ?>"/>
                    <?php        } ?>
                    <?php  if  ($type=='client') {
                    if ($langue=='francais'){ ?>
                    <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabn ?> - V/Réf: <?php echo $refclient ?>   - N/Réf: <?php echo $ref ?>"/>
                    <?php   }else{ ?>
                    <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabn ?> - Y/Ref: <?php echo $refclient ?>   - O/Ref: <?php echo $ref ?>"/>
                    <?php   }
                    ?>
                    <?php   } ?>
                </div>

                <?php    }
                ?>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <input id="description" type="text" class="form-control" name="description" id="description" required/>
                </div>


                <div class="form-group form-group-default">
                    <label id="attachem">Attachements de dossier</label>
                    <div class="row"  >
                        <div class="col-md-10">
                            <select id="attachs"  class="itemName form-control col-lg-12" style="" name="attachs[]"  multiple  value="$('#attachs').val()">
                                <option></option>
                                @foreach($attachements as $attach)
                                    <option <?php if($attach->parent== $entreeid || $attach->parent== $envoyeid  ){echo 'selected=selected';} ?> value="<?php echo $attach->id;?>" pathem="<?php  echo URL::asset('storage'.addslashes($attach->path)) ; ?>" typeem="<?php echo $attach->type;?>"><?php echo $attach->nom;?> </option>
                               <!--     <option value="<?php // echo $attach->id;?>"> <?php //echo $attach->nom;?> - <small><?php // echo date('d/m/Y H:i', strtotime($attach->created_at)); ?></small></option>-->
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <section>
                <div class="form-group ">
                    <label for="contenu">Contenu:</label>
                    <div class="editor" >
                        <textarea style="min-height: 350px;" id="contenu" type="text"  class="textarea tex-com" placeholder="Contenu de l'email ici" name="contenu" required  >
                         <?php echo  '<I> <b>De :</b> '. $de .'<br>';
                            echo '<b>A :</b> '. $to .'<br>';
                            echo'<b>Date :</b> '. $date.'<br>';
                            echo'<b>Sujet :</b> '.$sujet.'<br><br>';
                            echo $contenu .'</I><br><br>------------------<br>'; ?>
                         </textarea>
                    </div>
                </div>
                </section>
                <div class="form-group form-group-default">
                    <label>Attachements Externes <span style="color:red;">(la taille totale de fichiers ne doit pas dépasser 25 Mo)</span></label>
                    <!--<input  class="btn btn-danger fileinput-button" id="file" type="file" name="files[]"   multiple   >-->
                    <input type="file" class="btn btn-danger fileinput-button kfile" name="vasplus_multiple_files[]" id="vasplus_multiple_files" multiple="multiple" style="padding:5px;"/>      

                    <table class="table table-striped table-bordered" style="width:60%; border: none;" id="add_files">
                   <!--  <thead>
                        <tr>
                             <th style="color:blue; text-align:center;">File Name</th>
                            <th style="color:blue; text-align:center;">Status</th>
                            <th style="color:blue; text-align:center;">File Size</th> 
                            <th style="color:blue; text-align:center;">Action</th> 
                        <tr>
                    </thead> -->
                    <tbody>
                    
                    </tbody>
                </table>
                </div>
<!--
   {{--      {!! NoCaptcha::display() !!}  --}}
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
-->
                <button onclick=" resetForm(this.form);" id="SendBtn" type="submit"  name="myButton" class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>

            </form>

        </div>
    </div>

   
 <!-- Modal Document-->
    <div class="modal fade" id="voirfichier" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Aperçu de document</h4>
        </div>
        <div id="pdfbody" class="modal-body" style=" height: 700px; overflow-y: scroll;">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>


    <!-- Modal Document-->
    <div class="modal  " id="modalalert" >
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title"  id="modalalert0"><center>Attachements </center> </h2>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="row" >
                            <div class="alert alert-danger alert-dismissible   show" role="alert">

                                Avez-vous un fichier à attacher à ce mail ?
                            </div>
                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <a id="oui"  href="#attachem" class="btn btn-sucess"   style="width:100px;color:#ffffff" onclick="$('#modalalert').modal('hide');" >Oui</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Non</button>
                </div>
            </div>
        </div>
    </div>

     <!-- Modal Ouvrir Attachement-->
    <div class="modal fade" id="openattachEM"  role="dialog" aria-labelledby="exampleModal2" aria-hidden="true" style="z-index: 9999 !important">
        <div class="modal-dialog" role="document" style="width:900px;height: 450px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attTitleEM"></h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">


                        <iframe id="attachiframeEM" src="" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                         <center>   <img style="max-width:800px" id="imgattachEM"/></center>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <style>

        #oui  {background-color: #62c2e4;color :white;font-weight: bold;}
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <?php
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
    ?>
    <script type="text/javascript">

      /*  function checkBr()
        {
            if(document.getElementById('brsaved').value==1){
                alert();
                document.getElementById('SendBtn').disabled=false;
            }
        }*/
        function checkForm(form) // Submit button clicked
        {

            form.myButton.disabled = true;
            form.myButton.value = "Please wait...";
            return true;
        }

        function resetForm(form) // Reset button clicked
        {
            form.myButton.disabled = false;
            form.myButton.value = "Submit";
        }

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

            $('#theform').submit(function(){
                $(this).children('input[type=submit]').prop('disabled', true);
            });


            <?php if(  ($type!='prestataire') ||($prest>0) ) {  ?>
$('#modalalert').modal({show:true});
            <?php   }?>

$("#prest").change(function(){
                //  prest = $(this).val();
                var  prest =document.getElementById('prest').value;

                if (prest>0)
                {

                    window.location = '<?php echo $urlapp; ?>/emails/envoimailenreg/<?php echo $doss; ?>/prestataire/'+prest+'/<?php echo $entreeid; ?>/<?php echo $envoyeid; ?>';
                }else{
                    window.location = "<?php echo $urlapp; ?>/emails/envoimailenreg/<?php echo $doss; ?>/prestataire/0/<?php echo $entreeid; ?>/<?php echo $envoyeid; ?>";

                }


            });

            fileInputk = document.querySelector('#vasplus_multiple_files');
              fileInputk.addEventListener('change', function(event) {
                var inputk = event.target;

                if (inputk.files.length != khaled.length && inputk.files.length==0 )
                {
                      // alert ('ddd');
                       fileInputk.value="";
                    fileInputk.files= new FileListItem(khaled);

                }
                  });    



             /*$('#file').change(function(){
             var fp = $("#file");
             var lg = fp[0].files.length;
             var items = fp[0].files;
             var fileSize = 0;

             if (lg > 0) {
             for (var i = 0; i < lg; i++) {
             fileSize = fileSize+items[i].size; 
             }
             if(fileSize > 12000000 ) {
             alert('La taille des fichiers ne doit pas dépasser 12 MB');
             $('#file').val('');
             }
             }
             });*/




            // ajax save as draft
            $('#description').change(function(){
                var destinataire = $('#destinataire').val();
                var cc = $('#cc').val();
                var cci = $('#cci').val();
                var sujet = $('#sujet').val();
                var contenu = $('#contenu').val();
                var description = $('#description').val();
                var brsaved = $('#brsaved').val();

                if ( (brsaved==0) )
                { //alert('create br');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('envoyes.savingbr') }}",
                        method:"POST",
                        data:{description:description,destinataire:destinataire,sujet:sujet,contenu:contenu,cc:cc,cci:cci, _token:_token},
                        success:function(data){
                            //   alert('Brouillon enregistré ');

                            document.getElementById('envoye').value=data;
                            document.getElementById('brsaved').value=1;
                            document.getElementById('SendBtn').disabled=false;
                        }
                    });
                }else{

                    if ( description!='' )
                    {             var envoye = $('#envoye').val();

                        //  alert('update br');
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url:"{{ route('envoyes.updatingbr') }}",
                            method:"POST",
                            data:{envoye:envoye,description:description,destinataire:destinataire,contenu:contenu,cc:cc,cci:cci, _token:_token},
                            success:function(data){
                                //     alert('Brouillon enregistré ');

                                document.getElementById('envoye').value=data;
                                document.getElementById('brsaved').value=1;
                                document.getElementById('SendBtn').disabled=false;


                            }
                        });

                    }

                }
            });



            // ajax save as draft
            $('#sujet').change(function(){
                var destinataire = $('#destinataire').val();
                var cc = $('#cc').val();
                var cci = $('#cci').val();
                var sujet = $('#sujet').val();
                var contenu = $('#contenu').val();
                var description = $('#description').val();
                var brsaved = $('#brsaved').val();

                if ( (brsaved==0) )
                { //alert('create br');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('envoyes.savingbr') }}",
                        method:"POST",
                        data:{description:description,destinataire:destinataire,sujet:sujet,contenu:contenu,cc:cc,cci:cci, _token:_token},
                        success:function(data){
                            //   alert('Brouillon enregistré ');

                            document.getElementById('envoye').value=data;
                            document.getElementById('brsaved').value=1;
                            document.getElementById('SendBtn').disabled=false;

                        }
                    });
                }else{

                    if ( description!='' )
                    {             var envoye = $('#envoye').val();

                        //  alert('update br');
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url:"{{ route('envoyes.updatingbr') }}",
                            method:"POST",
                            data:{envoye:envoye,description:description,destinataire:destinataire,contenu:contenu,cc:cc,cci:cci, _token:_token},
                            success:function(data){
                                   alert('Brouillon enregistré ');

                                document.getElementById('envoye').value=data;
                                document.getElementById('brsaved').value=1;
                                document.getElementById('SendBtn').disabled=false;

                            }
                        });

                    }

                }
            });


            objTextBox = document.getElementById("contenu");
            oldValue = objTextBox.value;
            var somethingChanged = false;

            function track_change()
            {
                if(objTextBox.value != oldValue)
                {
                    oldValue = objTextBox.value;
                    somethingChanged = true;
                    changeeditor();
                };

                setTimeout(function() { track_change()}, 5000);

            }


            // setTimeout(function() { track_change()}, 15000);
            track_change();
            function setcontenu(myValue) {
                $('#contenu').val(myValue)
                    .trigger('change');
            }


            function changeeditor()
            {    var destinataire = $('#destinataire').val();
                var cc = $('#cc').val();
                var cci = $('#cci').val();
                var sujet = $('#sujet').val();
                var contenu = $('#contenu').val();
                var description = $('#description').val();
                var brsaved = $('#brsaved').val();

                if ((brsaved == 0)) {
                    //  alert('content changed');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('envoyes.savingbr') }}",
                        method: "POST",
                        data: {
                            description: description,
                            destinataire: destinataire,
                            sujet: sujet,
                            contenu: contenu,
                            cc: cc,
                            cci: cci,
                            _token: _token
                        },
                        success: function (data) {
                            //       alert('Brouillon enregistré ');

                            document.getElementById('envoye').value = data;
                            document.getElementById('brsaved').value = 1;
                            document.getElementById('SendBtn').disabled=false;

                        }
                    });
                } else {

                    //if ( description!='' )
                    //{
                    var envoye = $('#envoye').val();
                    // alert('updating br');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('envoyes.updatingbr') }}",
                        method: "POST",
                        data: {
                            description: description,
                            envoye: envoye,
                            destinataire: destinataire,
                            contenu: contenu,
                            cc: cc,
                            cci: cci,
                            _token: _token
                        },
                        success: function (data) {
                            //     alert('Brouillon enregistré ');
                            document.getElementById('envoye').value = data;
                            document.getElementById('brsaved').value = 1;
                            document.getElementById('SendBtn').disabled=false;

                        }
                    });

                    //  }

                }

            }

            $('#destinataire').select2({
                filter: true,
                language: {
                    noResults: function () {
                        return 'Pas de résultats';
                    }
                }
            });

            $('#attachs').select2({
                filter: true,
                language: {
                    noResults: function () {
                        return 'Pas de résultats';
                    }
                }
            });

            $('#cc').select2({
                filter: true,
                language: {
                    noResults: function () {
                        return 'Pas de résultats';
                    }
                }
            });

            $('#cci').select2({
                filter: true,
                language: {
                    noResults: function () {
                        return 'Pas de résultats';
                    }
                }
            });

        });
    </script>
<script>
    function modalattachEM(titre,emplacement,type)
    {
        document.getElementById('attachiframeEM').style.display='none';
        document.getElementById('imgattachEM').style.display='none';
          $("#attTitleEM").text(titre);

        if  (type ==  'pdf')
        {
            document.getElementById('attachiframeEM').src =emplacement;
            document.getElementById('attachiframeEM').style.display='block';

            // document.getElementById('attachiframe').src =emplacement;
        }
        if ( (type ==  'doc') || ( type ==  'docx'  ) || ( type ==  'xls'  ) || ( type ==  'xlsx'  )  )
        {document.getElementById('attachiframeEM').src ="https://view.officeapps.live.com/op/view.aspx?src="+emplacement;
            document.getElementById('attachiframeEM').style.display='block';
        }
        if ( (type ==  'png') || (type ==  'jpg') ||(type ==  'jpeg' ) )
        {
            document.getElementById('imgattachEM').style.display='block';
            document.getElementById('imgattachEM').src =emplacement;
           // document.getElementById('attachiframe').src =emplacement;
        }


        // cas DOC fichier DOC
        $("#openattachEM").modal('show');
    }
  
      var verrou=false;
    
     $(document).on('click','.select2-selection__choice__remove',function(){

        verrou=true;
      //alert('remove');

         });


    $(document).on('click','.select2-selection__choice',function(){
     var nomf=$(this).attr("title");
       // alert(nomf);

        //var texte = $("#attachs option:selected").text(); 
        //alert(texte);
        var values = '';
        if(verrou==false)
        {
        $('#attachs option').each(function() {

             //values += $(this).text(); 

            if($(this).text()==nomf)
            {
               empl=$(this).attr("pathem");
               type=$(this).attr("typeem");
               modalattachEM(nomf,empl,type);
               return false;
            }
           

         });
       }
       verrou=false;
                 //alert(values);
            })
</script>
<!--
    <script type="text/javascript">
        var onloadCallback = function() {
            console.log("grecaptcha is ready!");
        };
    </script>

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
            async defer>
    </script>
    -->
@endsection

