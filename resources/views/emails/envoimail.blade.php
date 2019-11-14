@extends('layouts.mainlayout')

@section('content')


    <link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />

     <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php use \App\Http\Controllers\EnvoyesController;     ?>
            <?php use \App\Http\Controllers\EntreesController;     ?>
            <?php use \App\Http\Controllers\PrestatairesController;     ?>
            <?php use \App\Http\Controllers\ClientsController;     ?>

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
            <?php if(isset($dossier)){?>   <h4 style="font-weight:bold;"><a  href="{{action('DossiersController@fiche',$dossier->id)}}" ><?php echo   $dossier->reference_medic .' - '.    \App\Http\Controllers\DossiersController::FullnameAbnDossierById($dossier->id);?> </a></h4><?php } ?>

<form id="theform" method="post" action="{{action('EmailController@send')}}"  enctype="multipart/form-data"  onsubmit="return checkForm(this);"  >
    <input id="dossier" type="hidden" class="form-control" name="dossier"  value="{{$doss}}" />
    <input id="envoye" type="hidden" class="form-control" name="envoye"  value="" />
    <input id="brsaved" type="hidden" class="form-control" name="brsaved"  value="0" />

    <div class="form-group">
        {{ csrf_field() }}
        <label for="from">Emetteur:</label>
        <div class="row">
            <select class="form-control" name="from" id="from" >
                <option value="faxnajdassist@najda-assistance.com">faxnajdassist@najda-assistance.com</option>
                <option value="24ops@najda-assistance.com">24ops@najda-assistance.com</option>
                <option value="hotels.vat@medicmultiservices.com">hotels.vat@medicmultiservices.com</option>
                <option value="assistance@medicmultiservices.com">assistance@medicmultiservices.com</option>
                <option value="ambulance.transp@medicmultiservices.com">ambulance.transp@medicmultiservices.com</option>
                <option value="vat.transp@medicmultiservices.com">vat.transp@medicmultiservices.com</option>
                <option value="operations@medicinternational.tn">operations@medicinternational.tn</option>
                <option value="tpa@najda-assistance.com">tpa@najda-assistance.com</option>
                <option value="taxi@najda-assistance.com">taxi@najda-assistance.com</option>
                <option value="xpress@najda-assistance.com">xpress@najda-assistance.com</option>
            </select>
        </div>
    </div>
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
                <option>ihebsaad@gmail.com</option>
                @foreach($listeemails as  $mail)
                    <option   value="<?php echo $mail ;?>"> <?php echo $mail ;?>  <small style="font-size:12px">(<?php echo PrestatairesController::NomByEmail( $mail);?>) - "<?php echo PrestatairesController::QualiteByEmail($mail);?>"</small> </option>
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
                    <option value="saadiheb@gmail.com">IHEB 2 (test)</option>
                    <option value="ihebs002@gmail.com">IHEB 3 (test)</option>
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
            <option value="saadiheb@gmail.com">IHEB 2 (test)</option>
            <option value="ihebs002@gmail.com">IHEB 3 (test)</option>
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
        </div>
    </div>

    <div class="form-group">
        <label for="sujet">Sujet :</label>
        <?php if($type=='prestataire')
        { ?>
        <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabn ?> - N/Réf(O/Ref): <?php echo $ref ?>"/>
  <?php }
        if ($type=='assure') {?>
        <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabn ?> - N/Réf(O/Ref): <?php echo $ref ?>"/>
<?php        } ?>
     <?php   if  ($type=='client') {?>
        <input id="sujet" type="text" class="form-control" name="sujet" required value="<?php echo $nomabn ?> - V/Réf(Y/Ref): <?php echo $refclient ?>   - N/Réf(O/Ref): <?php echo $ref ?>"/>
        <?php        } ?>
    </div>
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
                        <option value="<?php echo $attach->id;?>"> <?php echo $attach->nom;?> - <small><?php echo date('d/m/Y H:i', strtotime($attach->created_at)); ?></small></option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-group form-group-default">
        <label>Attachements Externes</label>
        <input  class="btn btn-danger fileinput-button" id="file" type="file" name="files[]"   multiple>
    </div>

    <div class="form-group ">
        <label for="contenu">Contenu:</label>
       <div class="editor" >
        <textarea style="min-height: 280px;" id="contenu" type="text"  class="textarea tex-com" placeholder="Contenu de l'email ici" name="contenu" required  ></textarea>
       </div>
    </div>

   {{--   {!! NoCaptcha::display() !!}   --}}
     <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <button onclick="resetForm(this.form);" id="SendBtn" type="submit"  name="myButton" class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>

 </form>

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

<style>

    #oui  {background-color: #62c2e4;color :white;font-weight: bold;}
 </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <?php
$urlapp="http://$_SERVER[HTTP_HOST]/najdaapp";
      ?>
<script type="text/javascript">

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

                window.location = '<?php echo $urlapp; ?>/emails/envoimail/<?php echo $doss; ?>/prestataire/'+prest;
             }else{
                window.location = "<?php echo $urlapp; ?>/emails/envoimail/<?php echo $doss; ?>/prestataire/0";

            }


        });


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

              setTimeout(function() { track_change()}, 15000);

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
    <script type="text/javascript">
        var onloadCallback = function() {
            console.log("grecaptcha is ready!");
        };
    </script>

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
            async defer>
    </script>

@endsection

