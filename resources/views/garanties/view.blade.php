@extends('layouts.adminlayout')

@section('content')
<?php


use App\RubriqueInitial ;

?>
    <div class="portlet box grey">
        <div class="modal-header"><h1>Garantie</h1></div>
    </div>
	<div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">
  
                    <div class="row">					
                        <div class="col-md-4">
                         <label for="inputError" class="control-label">Nom</label>
 		               <input onchange="changing(this)" type="text" class="form-control input" name="nom" id="nom"  value="{{ $garantie->nom }}">
                       </div>
					 <div class="col-md-8">
                         <label for="inputError" class="control-label">Description</label>
 		               <input onchange="changing(this)" type="text" class="form-control input" name="description" id="description"  value="{{ $garantie->description }}">
                      </div>	
	 
                    </div>
					
                       <input type="hidden" id="id" class="form-control"   value="{{ $garantie->id }}">
             </div>

    </form>
  </div>

   <div class="uper">
         <div class="portlet box grey">
            <div class="row">
                <div class="col-lg-6"><h3>Rubriques</h3></div>
                <div class="col-lg-6">
                    <button id="addgr" class="btn btn-md btn-success"   data-toggle="modal" data-target="#create"><b><i class="fas fa-plus"></i> Ajouter une rubrique</b></button>
                </div>
            </div>
        </div>
        <table class="table table-striped" id="mytable" style="width:100%">
            <thead>
            <tr id="headtable">
                <th style="width:10%">ID</th>
                 <th style="width:20%">Rubrique</th>
               <th style="width:10%">Montant</th>
                <th style="width:10%">Devise</th>
                 <th style="width:10%">Actions</th>
              </tr>
          <!--  <tr>
                <th style="width:10%">ID</th>
                 <th style="width:20%">nom</th>
                 <th style="width:30%">commentaire</th>				 
                <th style="width:10%">Montant</th>
                <th style="width:10%">Devise</th>
              <th class="no-sort" style="width:10%">Actions</th>
            </tr>-->
            </thead>
            <tbody>
            @foreach($rubriques as $rubrique)
       

                <tr>
                    <td  ><?php echo sprintf("%04d",$rubrique->id);?></td>
<?php $rubriquei=RubriqueInitial::where('id',$rubrique->rubriqueinitial)->first();?>
 					 <td  ><a href="{{action('RubriquesController@view', $rubrique->rubriqueinitial)}}" ><?php echo $rubriquei->nom;?></a></td>
 					
					<td><input type="number" class="form-control" style="width:100%" value="<?php echo $rubrique->montant ; ?>"  onchange="updating(<?php echo $rubrique->id;?>,'montant',this)"  /></td>
					<td>   <select class="form-control"   onchange="updating(<?php echo $rubrique->id;?>,'devise',this)" >
								 <option value="TND" <?php if($rubrique->devise=='TND'){echo 'selected="selected"';}  ?>  >TND</option>
								 <option value="EUR" <?php if($rubrique->devise=='EUR'){echo 'selected="selected"';}  ?> >EUR</option>
								 <option value="USD" <?php if($rubrique->devise=='USD'){echo 'selected="selected"';}  ?> >USD</option>
								 </select>
								 </td>
                      <td    >
                          @can('isAdmin')
                              <a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('GarantiesController@deleterubrique', $rubrique['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                  <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                              </a>
                          @endcan
                      </td>
 
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
	
	

 <!-- Modal -->
    <div class="modal fade" id="create"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter une table de garantie </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <form method="post" >
                            {{ csrf_field() }}


							
							
						   <div class="form-group">
                                <label for="rubriqueg">Rubrique :</label>
                                  <select class="form-control" style="width: 565px"  required id="rubriqueg" name="rubriqueg" >
                                        <option value="Select">Selectionner</option>
                                    <?php
                                       
                                        $rubriques = RubriqueInitial::orderBy('created_at','desc')->get();
                                        
                                    ?>
  
                                        @foreach ($rubriques as $rubrique)
                                        
                                      <option value={{ $rubrique["id"] }} >{{ $rubrique["nom"] }}</option>
                                                                                
                                        @endforeach

                                        
                                   </select>

                            </div>
						   <div class="form-group">
                                <label for="description">Montant :</label>
                                 <input class="form-control"  id="montant"  type="number" />

                            </div> 
																			
						 <div class="form-group">
                                <label for="nom">devise :</label>
                                 <select class="form-control"   id="devise"   >
								 <option value="TND">TND</option>
								 <option value="EUR">EUR</option>
								 <option value="USD">USD</option>
								 </select>

                            </div>
							
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
	
	
@endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<script type="text/javascript">


    $('#rubriqueg').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });
    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var garantie = $('#id').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('garanties.updating') }}",
            method: "POST",
            data: {garantie: garantie , champ:champ ,val:val, _token: _token},
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

	
	    function updating(rubrique,champ,elm) {
      
	//	var input=elm.id;
      //  var val =document.getElementById(input).value;
		 var val=elm.value;

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('garanties.updaterubrique') }}",
            method: "POST",
            data: { rubrique :rubrique,champ:champ ,val:val, _token: _token},
            success: function (data) {
                $(elm).animate({
                    opacity: '0.3',
                });
                $(elm).animate({
                    opacity: '1',
                });

            }
        });

    }
	
        $(document).ready(function() {
		
	     $('#add').click(function(){
                 var garantie = $('#id').val();
                var rubriqueinitial = $('#rubriqueg').val();
                var montant = $('#montant').val();
                var devise = $('#devise').val();
alert(rubriqueinitial );
                 if ((nom != '')  )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('garanties.savingRB') }}",
                        method:"POST",
                        data:{garantie:garantie,rubriqueinitial:rubriqueinitial,montant:montant,devise:devise , _token:_token},
                        success:function(data){

                            //   alert('Added successfully');
                         window.location =data;


                        }
                    });
                }else{
                    // alert('ERROR');
                }
            });
    });

</script>
