<!doctype html>
<head>
  <meta charset="utf-8">

   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

</head>
<body>



<h4>Appuyer sur le bouton suivant pour affecter le dossier</h4>


 <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modaffdoss">Open Modal</button>


   <input type="hidden" name="util_affecteur" value="{{Auth::user()->id }}">

    <div class="form-group form-group-default">
        <label> sélectionner Dossier à affecter </label>
        <div class="row">
            <div class="col-md-10">
        <select id="selaffdoss" class="itemName form-control col-lg-6" style="" name="selaffdoss"    value="$('#selaffdoss').val()">
            <option></option>
            @foreach($dossiers as $d)
                  
                <option value="<?php echo $d->reference_medic;?>"> </option>
               
            <?php ?>
            @endforeach
        </select>
            </div>
        </div>
     </div>
   <br>
     <div class="form-group form-group-default">
        <label> sélectionner l'utilisateur auquel vous voulez affecter le dossier</label>
        <div class="row">
            <div class="col-md-10">
        <select id="seluseraff" class="itemName form-control col-lg-6" style="width: 200px;" name="seluseraff"   
         value="$('#seluseraff').val()">
            <option></option>
            @foreach($users as $user)
                 
                <option value="<?php echo $user->id;?>"> <?php echo $user->name;?></option>
              
            <?php ?>
            @endforeach
        </select>
            </div>
        </div>
     </div>




  <!-- Modal -->
  <div class="modal fade" id="modaffdoss" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</body>
</html>