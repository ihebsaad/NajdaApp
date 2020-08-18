@extends('layouts.adminlayout')

@section('content')
    <div class="portlet box grey">
        <div class="modal-header">Contrat Client</div>
    </div> 
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
                                <label for="type">Commun / Particulier :</label>
                                <select class="form-control"  name="type" id="type" onchange="changing(this)"  >
								<option <?php if($contrat->type=='commun'){echo 'selected="selected"';} ?>  value="commun">Commun</option>
								<option <?php if($contrat->type=='particulier'){echo 'selected="selected"';} ?>  value="particulier">Particulier</option>
								</select>

                            </div>
                       </div>
					   
                 

       <input type="hidden" id="idtp" class="form-control"   value="{{ $contrat->id }}">
             </div>
	  <div class="row" style="padding:20px 20px 20px 20px">
 <h2 class="pull-left" >Natures des contrats</h2><span  class="btn btn-md btn-success pull-right"   data-toggle="modal" data-target="#create"><b><i class="fas fa-plus"></i> Ajouter une nature</b></span>
	  </div>
        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th style="width:10%">ID</th>
                <th style="width:45%">Nom</th>
                <th style="width:45%">Type</th>
                 <th style="width:10%">Actions</th>
              </tr>
            <tr>
                <th style="width:10%">ID</th>
                <th style="width:45%">Nom</th>
                <th style="width:45%">Type</th>
                 <th class="no-sort" style="width:10%">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($natures as $nature)
                <?php
          

                ?>

                <tr>
                    <td  ><?php echo sprintf("%04d",$nature->contrat);?></td>
                    <td  ><a href="{{action('ContratsController@nature', $nature['id'])}}" >{{$nature->nom}}</a></td>
                    <td  ><a href="{{action('ContratsController@nature', $nature['id'])}}" >{{$nature->type_dossier}}</a></td>
                      <td    >
                          @can('isAdmin')
                              <a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('ContratsController@destroy', $contrat['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                  <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                              </a>
                          @endcan
                      </td>
 
                </tr>
            @endforeach
            </tbody>
        </table>			 
			 
  <style>
	  img{cursor:pointer;}td{padding-left:10px;padding-right:10px;}
	  select{height:40px;margin-bottom:10px;}
	  input [type="number"],.number{width:100px;height:40px;font-weight:bold;font-size:18px;margin-top:-5px;}
  </style>
  

  </div>

  
  <!-- Modal -->
    <div class="modal fade" id="create"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter une nature de contrat</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <form method="post" >
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="type">Nom :</label>
                                <input class="form-control" type="text" id="nomc" />

                            </div>
							 
                         <input type="hidden" id="idcontrat" class="form-control"   value="{{ $contrat->id }}">

                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" id="add" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>  
  
  
  
  



<!--<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->
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

            $('#add').click(function(){
                var nom = $('#nomc').val();
                var contrat = $('#idcontrat').val();
				alert(nom+'  '+contrat);
                if ((nom != '')  )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('contrats.adding') }}",
                        method:"POST",
                        data:{nom:nom,contrat:contrat, _token:_token},
                        success:function(data){

                            //   alert('Added successfully');
                            window.location =data;


                        }
                    });
                }else{
                    // alert('ERROR');
                }
            });

</script>
@endsection